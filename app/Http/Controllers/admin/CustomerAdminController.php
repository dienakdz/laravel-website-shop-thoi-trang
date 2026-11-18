<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAdminController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Quản lý Khách hàng - DDien Admin';

        $totalCustomers = DB::table('customer')->count();
        $activeCustomers = DB::table('customer')->where('Status', 1)->count();
        $lockedCustomers = DB::table('customer')->where('Status', 0)->count();

        $query = DB::table('customer')
            ->leftJoin('orderproduct', 'customer.CustomerID', '=', 'orderproduct.CustomerID')
            ->select(
                'customer.*',
                DB::raw('COUNT(orderproduct.OrderID) as total_orders'),
                DB::raw('COALESCE(SUM(orderproduct.Totalprice), 0) as total_spent')
            )
            ->groupBy(
                'customer.CustomerID',
                'customer.Fullname',
                'customer.Gender',
                'customer.Email',
                'customer.Address',
                'customer.Phonenumber',
                'customer.Username',
                'customer.Password',
                'customer.Status'
            );

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->orWhere('customer.Fullname', 'like', "%{$search}%")
                  ->orWhere('customer.Username', 'like', "%{$search}%")
                  ->orWhere('customer.Email', 'like', "%{$search}%")
                  ->orWhere('customer.Phonenumber', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('customer.Status', $request->status);
        }

        $customers = $query->orderBy('customer.CustomerID', 'desc')->paginate(10)->withQueryString();

        return view('admin.customers.index', compact(
            'title',
            'customers',
            'totalCustomers',
            'activeCustomers',
            'lockedCustomers'
        ));
    }

    public function toggleStatus($id)
    {
        $customer = DB::table('customer')->where('CustomerID', $id)->first();
        if (!$customer) {
            return back()->with('msg_error', 'Không tìm thấy khách hàng!');
        }

        $newStatus = ($customer->Status == 1) ? 0 : 1;
        DB::table('customer')->where('CustomerID', $id)->update(['Status' => $newStatus]);

        $actionText = ($newStatus == 1) ? 'Kích hoạt mở khóa' : 'Khóa tài khoản';
        return back()->with('msg_success', "Đã {$actionText} cho khách hàng {$customer->Fullname} (@{$customer->Username}) thành công.");
    }
}
