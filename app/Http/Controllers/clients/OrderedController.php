<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Carts;
use App\Models\clients\Ordered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderedController extends Controller
{
    private $cartModel;
    private $orderedModel;

    public function __construct()
    {
        $this->cartModel = new Carts();
        $this->orderedModel = new Ordered();
    }

    public function index()
    {
        $title = 'Đơn mua của tôi';
        $customerID = $this->cartModel->getCustomerID(session('username'));

        if (!$customerID) {
            return redirect()->route('login')->with('msg_login', 'Vui lòng đăng nhập để xem đơn hàng!');
        }

        $Ordered = $this->orderedModel->getAllOrdered($customerID);
        $orderedDetails = [];

        foreach ($Ordered as $item) {
            $orderID = $item->OrderID;
            $orderedDetails[$orderID] = $this->orderedModel->getOrderedDetail($orderID);
        }

        return view('clients/ordered', compact('title', 'Ordered', 'orderedDetails'));
    }

    // Cập nhật trạng thái đơn hàng (Vá lỗ hổng IDOR)
    public function updateStatus($id = 0)
    {
        $customerID = $this->cartModel->getCustomerID(session('username'));

        // Kiểm tra quyền sở hữu đơn hàng
        $order = DB::table('orderproduct')
            ->where('OrderID', $id)
            ->where('CustomerID', $customerID)
            ->first();

        if (!$order) {
            abort(403, 'Bạn không có quyền thao tác trên đơn hàng này!');
        }

        // Cập nhật trạng thái đã nhận được hàng (Status = 4)
        $this->orderedModel->updateStatus($id);

        return redirect()->route('ordered')->with('msg_status_success', 'Đã cập nhật trạng thái đơn hàng!');
    }
}
