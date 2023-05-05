<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Checkout;
use App\Models\clients\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DateTime;
use DateTimeZone;

class CheckoutController extends Controller
{
    private $getCustomerID;
    private $getProduct;
    private $addCheckout;

    public function __construct()
    {
        $this->getCustomerID = new Carts();
        $this->getProduct = new Checkout();
        $this->addCheckout = new Checkout();
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
            return redirect()->route('product')->with('msg','Vui lòng thêm sản phẩm vào giỏ hàng!');
        }


        return view('clients/checkout', compact('title', 'productCheckOut', 'totalPrice'));
    }


    public function create(Request $request)
    {
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

        // $path = public_path('assets/clients/json/data.json');

        // $data = file_get_contents($path);
        //  dd($data);
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

        if (!empty($request->input('productID', []))) {
            $filters = [];
            $productIDs = $request->input('productID', []);

            foreach ($productIDs as $id) {
                $filters = [['carts.productID', '=', $id]];
                $this->addCheckout->updateStatusCart($filters, $customerID);
            }
        }
        $this->addCheckout->addCheckout($checkout);

        return "Đặt hàng thành công! Đơn hàng của bạn đang đợi người bán xác nhận!";

    }


}