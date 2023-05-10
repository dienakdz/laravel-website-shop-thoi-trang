<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Checkout;
use App\Models\clients\Carts;
use App\Models\clients\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DateTime;
use DateTimeZone;

class CheckoutController extends Controller
{
    private $getCustomerID;
    private $getProduct;
    private $addCheckout;
    private $selectOderID;

    private $updateQuantity;
    public function __construct()
    {
        $this->updateQuantity = new Products();
        $this->getCustomerID = new Carts();
        $this->getProduct = new Checkout();
        $this->addCheckout = new Checkout();
        $this->selectOderID = new Checkout();
    }

    public function index()
    {
        $title = 'Thanh toán';
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        $productCheckOut = $this->getProduct->productCheckOut($customerID);
        $totalPrice = 0;
        if ($productCheckOut) {
            foreach ($productCheckOut as $item) {
                $totalPrice += $item->Price * $item->CartQuantity;
            }
        }
        if (count($productCheckOut) < 1) {
            return redirect()->route('product')->with('msg', 'Vui lòng thêm sản phẩm vào giỏ hàng!');
        }


        return view('clients/checkout', compact('title', 'productCheckOut', 'totalPrice'));
    }

    //Tạo checkout
    public function create(Request $request)
    {
        //Lấy id của customer dựa vào session 
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

        $data = file_get_contents(public_path('assets/clients/json/data.json'));
        $diaGioiHanhChinh = json_decode($data, true);

        $tinhThanhPho = '';
        $quanHuyen = '';
        $xaPhuong = '';

        $tinhTp = array_filter($diaGioiHanhChinh, function ($item) use ($request) {
            return $item['Id'] == $request->tinhthanhpho;
        });

        if (!empty($tinhTp)) {
            $tinhTp = reset($tinhTp);
            $tinhThanhPho = $tinhTp['Name'];

            $quanHuyens = array_filter($tinhTp['Districts'], function ($item) use ($request) {
                return $item['Id'] == $request->quanhuyen;
            });

            if (!empty($quanHuyens)) {
                $quanHuyens = reset($quanHuyens);
                $quanHuyen = $quanHuyens['Name'];

                $xaPhuongs = array_filter($quanHuyens['Wards'], function ($item) use ($request) {
                    return $item['Id'] == $request->xaphuong;
                });

                if (!empty($xaPhuongs)) {
                    $xaPhuongs = reset($xaPhuongs);
                    $xaPhuong = $xaPhuongs['Name'];
                }
            }
        }
        $checkout = [
            'OrderDate' => $date->format('Y-m-d H:i:s'),
            'Fulname' => $request->name,
            'Phonenumber' => $request->sdt,
            'Address' => $tinhThanhPho . " - " . $xaPhuong . " - " . $quanHuyen . " - " . $request->address,
            'Shippingtype' => $request->thanhtoan,
            'Totalprice' => $request->totalPrice,
            'CustomerID' => $customerID,
            'Status' => 1
        ];

        $orderProduct = $this->addCheckout->addCheckout($checkout);
        if ($orderProduct) {
            //Lấy OrderID 
            $OrderID = $this->selectOderID->selectOderID();
            //Lấy tất cả sản phẩm checkout của customer đó để xuống dưới nhập vào orderdetail
            $productCheckOuts = $this->getProduct->productCheckOut($customerID);
            //Duyệt tất cả các sản phẩm được lấy ra
            foreach ($productCheckOuts as $productCheckOut) {
                $productID = $productCheckOut->ProductID;
                $orderdetail = [
                    'OrderID' => $OrderID,
                    'ProductID' => $productID,
                    'QuantityOrdered' => $productCheckOut->CartQuantity,
                    'Price' => $productCheckOut->CartQuantity * $productCheckOut->Price
                ];
                //Tiến hành thêm sản phẩm vào orderdetail
                $this->addCheckout->addOrderDetail($orderdetail);
                //tiến hành update lại sản phẩm trong kho, bằng tổng sp hiện có trừ cho số sản phẩm được đặt
                $newQuantity = $productCheckOut->Quantity - $productCheckOut->CartQuantity;
                //Sau khi thêm thành công thì tiến hàng giảm số lượng sản phẩm trong kho xuống theo sl đã được đặt
                $this->updateQuantity->updateQuantityProduct($productID, $newQuantity);

            }

            //Kiểm tra số lượng sản phẩm gửi lên và update trạng thái cho nó 
            if (!empty($request->input('productID', []))) {
                $filters = [];
                $productIDs = $request->input('productID', []);

                foreach ($productIDs as $id) {
                    $filters = [['carts.productID', '=', $id]];
                    $this->addCheckout->updateStatusCart($filters, $customerID);
                }
            }
        }

        return redirect()->route('ordered');

    }

}