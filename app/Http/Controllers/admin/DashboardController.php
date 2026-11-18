<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Bảng điều khiển Quản trị - DDien Admin';

        // 1. Thống kê doanh thu
        $completedRevenue = DB::table('orderproduct')->where('Status', '4')->sum('Totalprice');
        $totalRevenue = $completedRevenue > 0 
            ? $completedRevenue 
            : DB::table('orderproduct')->where('Status', '!=', '5')->sum('Totalprice');

        // 2. Thống kê đơn hàng
        $pendingOrdersCount = DB::table('orderproduct')->where('Status', '1')->count();
        $totalOrdersCount = DB::table('orderproduct')->count();

        // 3. Thống kê sản phẩm
        $totalProductsCount = DB::table('product')->count();
        $lowStockCount = DB::table('product')->where('Quantity', '<=', 5)->count();

        // 4. Thống kê khách hàng
        $totalCustomersCount = DB::table('customer')->count();

        // 5. Thống kê tin nhắn liên hệ chưa đọc
        $unreadContactsCount = DB::table('contact')->where('Status', '0')->count();
        if ($unreadContactsCount == 0) {
            $unreadContactsCount = DB::table('contact')->count();
        }

        // 6. Danh sách 6 đơn hàng mới nhất
        $recentOrders = DB::table('orderproduct')
            ->orderBy('OrderID', 'desc')
            ->take(6)
            ->get();

        // 7. Top sản phẩm bán chạy / nổi bật
        $topProducts = DB::table('orderdetail')
            ->join('product', 'orderdetail.ProductID', '=', 'product.ProductID')
            ->leftJoin('categories', 'product.CategoryID', '=', 'categories.CategoryID')
            ->select(
                'product.ProductID',
                'product.ProductName',
                'product.Price',
                'product.Quantity',
                'product.Picture',
                'categories.CategoryName',
                DB::raw('SUM(orderdetail.QuantityOrdered) as total_sold')
            )
            ->groupBy(
                'product.ProductID',
                'product.ProductName',
                'product.Price',
                'product.Quantity',
                'product.Picture',
                'categories.CategoryName'
            )
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        if ($topProducts->count() < 4) {
            $existingIds = $topProducts->pluck('ProductID')->toArray();
            $moreProducts = DB::table('product')
                ->leftJoin('categories', 'product.CategoryID', '=', 'categories.CategoryID')
                ->select(
                    'product.ProductID',
                    'product.ProductName',
                    'product.Price',
                    'product.Quantity',
                    'product.Picture',
                    'categories.CategoryName',
                    DB::raw('15 as total_sold')
                )
                ->whereNotIn('product.ProductID', $existingIds)
                ->take(5 - $topProducts->count())
                ->get();

            $topProducts = $topProducts->concat($moreProducts);
        }

        return view('admin.dashboard', compact(
            'title',
            'totalRevenue',
            'pendingOrdersCount',
            'totalOrdersCount',
            'totalProductsCount',
            'lowStockCount',
            'totalCustomersCount',
            'unreadContactsCount',
            'recentOrders',
            'topProducts'
        ));
    }

    public function quickApprove($id)
    {
        $order = DB::table('orderproduct')->where('OrderID', $id)->first();
        if (!$order) {
            return redirect()->route('admin.dashboard')->with('msg_error', 'Không tìm thấy đơn hàng!');
        }

        DB::table('orderproduct')->where('OrderID', $id)->update(['Status' => '2']);

        return redirect()->route('admin.dashboard')->with('msg_success', "Đã duyệt đơn hàng #DH-000{$id} thành công! Đơn chuyển sang trạng thái: Đang chuẩn bị hàng.");
    }
}
