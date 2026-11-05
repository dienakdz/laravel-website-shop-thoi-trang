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

    public function index(Request $request)
    {
        $title = 'Đơn mua của tôi';
        $customerID = $this->cartModel->getCustomerID(session('username'));

        if (!$customerID) {
            return redirect()->route('login')->with('msg_login', 'Vui lòng đăng nhập để xem đơn hàng!');
        }

        $currentStatus = $request->get('status', 'all');

        // Lấy toàn bộ đơn hàng của khách hàng (đơn mới nhất lên đầu)
        $allOrders = $this->orderedModel->getAllOrdered($customerID);

        // Đếm số lượng đơn cho từng trạng thái
        $counts = [
            'all' => $allOrders->count(),
            '1' => $allOrders->where('Status', 1)->count(), // Chờ xác nhận
            '2' => $allOrders->where('Status', 2)->count(), // Đã xác nhận / Đang chuẩn bị
            '3' => $allOrders->where('Status', 3)->count(), // Đang giao
            '4' => $allOrders->where('Status', 4)->count(), // Đã giao / Hoàn thành
            '0' => $allOrders->where('Status', 0)->count(), // Đã hủy
        ];

        // Lọc đơn hàng theo tab trạng thái đang chọn
        if ($currentStatus !== 'all') {
            $Ordered = $allOrders->where('Status', $currentStatus);
        } else {
            $Ordered = $allOrders;
        }

        // Lấy chi tiết các sản phẩm của từng đơn hàng
        $orderedDetails = [];
        foreach ($Ordered as $item) {
            $orderID = $item->OrderID;
            $orderedDetails[$orderID] = $this->orderedModel->getOrderedDetail($orderID);
        }

        return view('clients/ordered', compact('title', 'Ordered', 'orderedDetails', 'currentStatus', 'counts'));
    }

    // Khách hàng xác nhận "Đã nhận được hàng" (Status = 3 -> 4)
    public function updateStatus($id = 0)
    {
        $customerID = $this->cartModel->getCustomerID(session('username'));

        // Kiểm tra quyền sở hữu đơn hàng (Vá lỗ hổng IDOR)
        $order = DB::table('orderproduct')
            ->where('OrderID', $id)
            ->where('CustomerID', $customerID)
            ->first();

        if (!$order) {
            abort(403, 'Bạn không có quyền thao tác trên đơn hàng này!');
        }

        // Chỉ cho phép xác nhận khi đơn đang ở trạng thái Đang giao (Status = 3)
        if ($order->Status != 3) {
            return redirect()->route('ordered')->with('error', 'Đơn hàng chưa ở trạng thái đang giao!');
        }

        $this->orderedModel->updateStatus($id);

        return redirect()->route('ordered')->with('msg_status_success', 'Cảm ơn bạn! Đã xác nhận nhận hàng thành công.');
    }

    // Khách hàng hủy đơn hàng (Status = 1 -> 0)
    public function cancelOrder($id = 0)
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

        // Chỉ cho phép hủy khi đơn hàng đang ở trạng thái Chờ xác nhận (Status = 1)
        if ($order->Status != 1) {
            return redirect()->route('ordered')->with('error', 'Chỉ có thể hủy đơn hàng khi shop chưa xác nhận đơn!');
        }

        DB::beginTransaction();
        try {
            // Cập nhật trạng thái thành Đã hủy (Status = 0)
            $this->orderedModel->cancelOrder($id);

            // Hoàn lại số lượng tồn kho cho các sản phẩm trong đơn
            $details = DB::table('orderdetail')->where('OrderID', $id)->get();
            foreach ($details as $item) {
                DB::table('product')
                    ->where('ProductID', $item->ProductID)
                    ->increment('Quantity', $item->QuantityOrdered);
            }

            DB::commit();
            return redirect()->route('ordered')->with('msg_status_success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ordered')->with('error', 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage());
        }
    }
}
