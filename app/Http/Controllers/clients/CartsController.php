<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartsController extends Controller
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new Carts();
    }

    public function index()
    {
        $title = 'Giỏ hàng của bạn';
        $customerID = $this->cartModel->getCustomerID(session('username'));

        if (!$customerID) {
            return redirect()->route('login')->with('msg_login', 'Vui lòng đăng nhập để xem giỏ hàng!');
        }

        $getAllCarts = $this->cartModel->getCarts($customerID);
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
        $customerID = $this->cartModel->getCustomerID(session('username'));
        $productID = $request->productID;
        $qty = max(1, (int)$request->qty);

        // Kiểm tra tồn kho
        $product = DB::table('product')->where('ProductID', $productID)->first();
        if ($product && $qty > $product->Quantity) {
            $qty = $product->Quantity;
        }

        $qtycart = ['CartQuantity' => $qty];
        $this->cartModel->updateQuantity($customerID, $productID, $qtycart);

        // Tính tổng tiền 1 sản phẩm
        $product_item = $this->cartModel->getItemProduct($customerID, $productID);
        $totalProduct = 0;
        foreach ($product_item as $item) {
            $totalProduct += $item->Price * $item->CartQuantity;
        }

        // Tính tổng tiền giỏ hàng
        $items = $this->cartModel->getCarts($customerID);
        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item->Price * $item->CartQuantity;
        }

        return response()->json([
            'totalPrice' => $totalPrice,
            'totalProduct' => $totalProduct,
            'currentQty' => $qty
        ]);
    }

    public function addCart(Request $request, $productID = 0)
    {
        $customerID = $this->cartModel->getCustomerID(session('username'));

        if (!$customerID) {
            return redirect()->route('login')->with('msg_login', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!');
        }

        $requestQty = max(1, (int)($request->cartquantity ?? 1));

        // Kiểm tra tồn kho của sản phẩm
        $product = DB::table('product')->where('ProductID', $productID)->first();
        if (!$product || $product->Quantity <= 0) {
            return redirect()->back()->with('msg_error', 'Sản phẩm này hiện đang tạm hết hàng!');
        }

        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $check_product_customer = $this->cartModel->getProductCustomer($customerID, $productID);

        if (count($check_product_customer) > 0) {
            $currentCartQty = $check_product_customer->first()->CartQuantity;
            $newQty = min($product->Quantity, $currentCartQty + $requestQty);
            $this->cartModel->updateCart($customerID, $productID, ['CartQuantity' => $newQty]);
        } else {
            $initialQty = min($product->Quantity, $requestQty);
            $cartData = [
                'CustomerID' => $customerID,
                'ProductID' => $productID,
                'CartQuantity' => $initialQty,
                'Status' => 0
            ];
            $this->cartModel->addCart($cartData);
        }

        return redirect()->route('carts')->with('msg_success', 'Thêm vào giỏ hàng thành công!');
    }

    public function deleteProductCart($cartID = 0)
    {
        $customerID = $this->cartModel->getCustomerID(session('username'));

        // Bảo mật: chỉ cho xóa nếu sản phẩm trong giỏ thuộc về đúng khách hàng hiện tại
        DB::table('carts')
            ->where('CartID', $cartID)
            ->where('CustomerID', $customerID)
            ->delete();

        return response()->json(['success' => true]);
    }
}
