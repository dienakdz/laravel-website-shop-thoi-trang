<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Checkout;
use App\Models\clients\Carts;
use App\Models\clients\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;

class CheckoutController extends Controller
{
    private $cartModel;
    private $checkoutModel;
    private $productModel;

    public function __construct()
    {
        $this->cartModel = new Carts();
        $this->checkoutModel = new Checkout();
        $this->productModel = new Products();
    }

    public function index()
    {
        $title = 'Thanh toán đơn hàng';
        $customerID = $this->cartModel->getCustomerID(session('username'));

        if (!$customerID) {
            return redirect()->route('login')->with('msg_login', 'Vui lòng đăng nhập!');
        }

        $productCheckOut = $this->checkoutModel->productCheckOut($customerID);
        $totalPrice = 0;

        if ($productCheckOut) {
            foreach ($productCheckOut as $item) {
                $totalPrice += $item->Price * $item->CartQuantity;
            }
        }

        if (count($productCheckOut) < 1) {
            return redirect()->route('product')->with('msg', 'Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ!');
        }

        return view('clients/checkout', compact('title', 'productCheckOut', 'totalPrice'));
    }

    // Xử lý tạo đơn hàng
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sdt' => 'required',
            'address' => 'required',
            'thanhtoan' => 'required',
        ]);

        $customerID = $this->cartModel->getCustomerID(session('username'));
        if (!$customerID) {
            return redirect()->route('login')->with('msg_login', 'Vui lòng đăng nhập!');
        }

        // Lấy danh sách sản phẩm trong giỏ hàng
        $productCheckOuts = $this->checkoutModel->productCheckOut($customerID);
        if (count($productCheckOuts) < 1) {
            return redirect()->route('carts')->with('msg_cart_error', 'Giỏ hàng của bạn đang trống!');
        }

        // Kiểm tra số lượng tồn kho trước khi đặt
        foreach ($productCheckOuts as $item) {
            if ($item->Quantity < $item->CartQuantity) {
                return redirect()->route('carts')->with('msg_cart_error', 'Sản phẩm "' . $item->ProductName . '" không đủ số lượng tồn kho (chỉ còn ' . $item->Quantity . ' sản phẩm). Vui lòng cập nhật lại giỏ hàng!');
            }
        }

        // Phân giải địa giới hành chính
        $tinhThanhPho = '';
        $quanHuyen = '';
        $xaPhuong = '';

        $jsonPath = public_path('assets/clients/json/data.json');
        if (file_exists($jsonPath)) {
            $diaGioiHanhChinh = json_decode(file_get_contents($jsonPath), true) ?? [];

            $tinhTp = array_filter($diaGioiHanhChinh, function ($item) use ($request) {
                return $item['Id'] == $request->tinhthanhpho;
            });

            if (!empty($tinhTp)) {
                $tinhTp = reset($tinhTp);
                $tinhThanhPho = $tinhTp['Name'] ?? '';

                $quanHuyens = array_filter($tinhTp['Districts'] ?? [], function ($item) use ($request) {
                    return $item['Id'] == $request->quanhuyen;
                });

                if (!empty($quanHuyens)) {
                    $quanHuyens = reset($quanHuyens);
                    $quanHuyen = $quanHuyens['Name'] ?? '';

                    $xaPhuongs = array_filter($quanHuyens['Wards'] ?? [], function ($item) use ($request) {
                        return $item['Id'] == $request->xaphuong;
                    });

                    if (!empty($xaPhuongs)) {
                        $xaPhuongs = reset($xaPhuongs);
                        $xaPhuong = $xaPhuongs['Name'] ?? '';
                    }
                }
            }
        }

        $fullAddress = trim(implode(' - ', array_filter([$request->address, $xaPhuong, $quanHuyen, $tinhThanhPho])));

        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

        // Tính lại tổng tiền chính xác từ DB
        $totalPrice = 0;
        foreach ($productCheckOuts as $item) {
            $totalPrice += $item->Price * $item->CartQuantity;
        }

        // Bọc toàn bộ vào DB Transaction đảm bảo an toàn tuyệt đối
        DB::beginTransaction();
        try {
            // 1. Tạo đơn hàng và lấy chính xác OrderID vừa tạo
            $checkoutData = [
                'OrderDate' => $date->format('Y-m-d H:i:s'),
                'Fulname' => $request->name,
                'Phonenumber' => $request->sdt,
                'Address' => $fullAddress,
                'Shippingtype' => $request->thanhtoan,
                'Totalprice' => $totalPrice,
                'CustomerID' => $customerID,
                'Status' => 1 // 1: Đặt hàng thành công / Chờ xác nhận
            ];

            $orderID = $this->checkoutModel->addCheckout($checkoutData);

            // 2. Thêm chi tiết đơn hàng & trừ số lượng tồn kho
            foreach ($productCheckOuts as $product) {
                $orderdetail = [
                    'OrderID' => $orderID,
                    'ProductID' => $product->ProductID,
                    'QuantityOrdered' => $product->CartQuantity,
                    'Price' => $product->Price * $product->CartQuantity
                ];
                $this->checkoutModel->addOrderDetail($orderdetail);

                // Trừ số lượng tồn kho
                $newQuantity = max(0, $product->Quantity - $product->CartQuantity);
                $this->productModel->updateQuantityProduct($product->ProductID, $newQuantity);
            }

            // 3. Đánh dấu các sản phẩm trong giỏ hàng là đã đặt (Status = 1)
            $this->checkoutModel->updateStatusCart([], $customerID);

            DB::commit();

            return redirect()->route('ordered')->with('msg_order_success', 'Đặt hàng thành công! Đơn hàng của bạn đang được xử lý.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout')->with('msg_checkout_error', 'Có lỗi xảy ra trong quá trình đặt hàng: ' . $e->getMessage());
        }
    }
}
