<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Quản lý Đơn hàng - Eiser Admin';

        // 1. Thống kê số lượng đơn hàng theo từng trạng thái
        $allCount = DB::table('orderproduct')->count();
        $pendingCount = DB::table('orderproduct')->where('Status', '1')->count();
        $preparingCount = DB::table('orderproduct')->where('Status', '2')->count();
        $shippingCount = DB::table('orderproduct')->where('Status', '3')->count();
        $completedCount = DB::table('orderproduct')->where('Status', '4')->count();
        $cancelledCount = DB::table('orderproduct')->where('Status', '5')->count();

        // 2. Query danh sách đơn hàng
        $query = DB::table('orderproduct');

        // Lọc theo Tab trạng thái
        $currentStatus = $request->query('status', 'all');
        if ($currentStatus !== 'all' && in_array($currentStatus, ['1', '2', '3', '4', '5'])) {
            $query->where('Status', $currentStatus);
        }

        // Tìm kiếm theo mã đơn, họ tên, số điện thoại, địa chỉ
        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $cleanId) {
                if ($cleanId !== '') {
                    $q->orWhere('OrderID', (int)$cleanId);
                }
                $q->orWhere('Fulname', 'like', "%{$search}%")
                  ->orWhere('Phonenumber', 'like', "%{$search}%")
                  ->orWhere('Address', 'like', "%{$search}%");
            });
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('shippingtype') && $request->shippingtype !== 'all') {
            $query->where('Shippingtype', 'like', "%{$request->shippingtype}%");
        }

        // Lọc theo thời gian
        if ($request->filled('time') && $request->time !== 'all') {
            if ($request->time === 'today') {
                $query->whereDate('OrderDate', date('Y-m-d'));
            } elseif ($request->time === 'this_month') {
                $query->whereMonth('OrderDate', date('m'))->whereYear('OrderDate', date('Y'));
            }
        }

        $orders = $query->orderBy('OrderID', 'desc')->paginate(10)->withQueryString();

        // 3. Đơn hàng đang được xem chi tiết (preview_id hoặc đơn đầu tiên)
        $previewId = $request->query('preview_id');
        $previewOrder = null;
        $previewItems = collect();

        if ($previewId) {
            $previewOrder = DB::table('orderproduct')->where('OrderID', $previewId)->first();
        }

        if (!$previewOrder && $orders->count() > 0) {
            $previewOrder = $orders->first();
        }

        if ($previewOrder) {
            $previewItems = DB::table('orderdetail')
                ->leftJoin('product', 'orderdetail.ProductID', '=', 'product.ProductID')
                ->where('orderdetail.OrderID', $previewOrder->OrderID)
                ->select(
                    'orderdetail.*',
                    'product.ProductName',
                    'product.Picture'
                )
                ->get();
        }

        return view('admin.orders.index', compact(
            'title',
            'orders',
            'allCount',
            'pendingCount',
            'preparingCount',
            'shippingCount',
            'completedCount',
            'cancelledCount',
            'currentStatus',
            'previewOrder',
            'previewItems'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,2,3,4,5'
        ]);

        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            $order = DB::table('orderproduct')->where('OrderID', $id)->first();
            if (!$order) {
                return back()->with('msg_error', 'Không tìm thấy đơn hàng cần cập nhật!');
            }

            $oldStatus = $order->Status;

            // Xử lý hoàn / trừ kho khi chuyển trạng thái Hủy đơn
            if ($oldStatus != '5' && $newStatus == '5') {
                // Đang hoạt động chuyển sang Hủy -> Hoàn lại tồn kho
                $items = DB::table('orderdetail')->where('OrderID', $id)->get();
                foreach ($items as $item) {
                    DB::table('product')->where('ProductID', $item->ProductID)
                        ->increment('Quantity', $item->QuantityOrdered);
                }
            } elseif ($oldStatus == '5' && $newStatus != '5') {
                // Từ Hủy chuyển lại Hoạt động -> Kiểm tra tồn kho & trừ lại
                $items = DB::table('orderdetail')->where('OrderID', $id)->get();
                foreach ($items as $item) {
                    $prod = DB::table('product')->where('ProductID', $item->ProductID)->first();
                    if ($prod && $prod->Quantity < $item->QuantityOrdered) {
                        DB::rollBack();
                        return back()->with('msg_error', "Sản phẩm '{$prod->ProductName}' không đủ tồn kho (chỉ còn {$prod->Quantity}) để kích hoạt lại đơn!");
                    }
                }
                foreach ($items as $item) {
                    DB::table('product')->where('ProductID', $item->ProductID)
                        ->decrement('Quantity', $item->QuantityOrdered);
                }
            }

            // Cập nhật trạng thái
            DB::table('orderproduct')->where('OrderID', $id)->update(['Status' => $newStatus]);

            DB::commit();

            $statusLabels = [
                '1' => 'Chờ xác nhận',
                '2' => 'Đang chuẩn bị hàng',
                '3' => 'Đang giao hàng',
                '4' => 'Hoàn thành',
                '5' => 'Đã hủy đơn'
            ];

            $label = $statusLabels[$newStatus] ?? $newStatus;

            return redirect()->route('admin.orders.index', [
                'preview_id' => $id,
                'status' => $request->input('current_tab', 'all')
            ])->with('msg_success', "Đã cập nhật đơn hàng #DH-" . str_pad($id, 4, '0', STR_PAD_LEFT) . " sang trạng thái: {$label}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('msg_error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function printInvoice($id)
    {
        $order = DB::table('orderproduct')->where('OrderID', $id)->first();
        if (!$order) {
            return redirect()->route('admin.orders.index')->with('msg_error', 'Không tìm thấy đơn hàng!');
        }

        $items = DB::table('orderdetail')
            ->leftJoin('product', 'orderdetail.ProductID', '=', 'product.ProductID')
            ->where('orderdetail.OrderID', $id)
            ->select('orderdetail.*', 'product.ProductName')
            ->get();

        $title = "Hóa đơn đơn hàng #DH-" . str_pad($id, 4, '0', STR_PAD_LEFT);

        return view('admin.orders.print', compact('order', 'items', 'title'));
    }
}
