<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Carts;
use Illuminate\Http\Request;

class CartsController extends Controller
{


    private $getCarts;

    private $addCart;

    private $updateCart;
    private $getCustomerID;

    private $getProductCustomer;

    private $updateQuantity;
    private $deleteProduct;
    public function __construct()
    {
        $this->getCarts = new Carts();
        $this->addCart = new Carts();
        $this->updateCart = new Carts();
        $this->getCustomerID = new Carts();
        $this->getProductCustomer = new Carts();
        $this->updateQuantity = new Carts();
        $this->deleteProduct = new Carts();
    }
    public function index()
    {
        $title = 'Giỏ hàng';
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        $getAllCarts = $this->getCarts->getCarts($customerID);
        $totalPrice = 0;
        if ($getAllCarts) {
            foreach ($getAllCarts as $item) {
                $totalPrice += $item->Price * $item->CartQuantity;
            }
        }
        return view('clients/carts', compact('title', 'getAllCarts', 'totalPrice'));
    }

    public function updateQuantity(Request $request)
    {
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        $productID = $request->productID;
        $qtycart =
            [
                'CartQuantity' => $request->qty
            ];

        $this->updateQuantity->updateQuantity($customerID, $productID, $qtycart);

        // Tính tổng tiền 1 sản phẩm
        $product_item = $this->getCarts->getItemProduct($customerID, $productID);
        $totalProduct = 0;
        foreach ($product_item as $item) {
            $totalProduct += $item->Price * $item->CartQuantity;
        }

        // Tính tổng tiền
        $items = $this->getCarts->getCarts($customerID);
        // dd($items);
        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item->Price * $item->CartQuantity;
        }

        return response()->json([
            'totalPrice' => $totalPrice,
            'totalProduct' => $totalProduct
        ]);
    }

    public function addCart(Request $request, $productID = 0)
    {
        $cart = [];
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        if (session('username')) {
            //check sản phẩm đã có hay chưa

            $check_product_customer = $this->getProductCustomer->getProductCustomer($customerID, $productID);

            if (count($check_product_customer) > 0) {
                $cartQuantity = $check_product_customer->first()->CartQuantity;
                $cart =
                    [
                        'CartQuantity' => $request->cartquantity + $cartQuantity
                    ];
                $this->updateCart->updateCart($customerID, $productID, $cart);
            } else {
                $cart =
                    [
                        'CustomerID' => $customerID,
                        'ProductID' => $productID,
                        'CartQuantity' => $request->cartquantity,
                        'Status' => 0
                    ];

                $this->addCart->addCart($cart);
                // dd(count($check_product_customer));
            }
            return redirect()->route('carts');
        } else {
            echo '<script>alert("Vui lòng đăng nhập để mua sản phẩm!")</script>';
            return redirect()->route('login');
        }

    }

    public function deleteProductCart($cartID = 0)
    {
        $this->deleteProduct->deleteProductCart($cartID);
        return response()->json(['success' => true]);
        // return redirect()->route('carts');
    }

}