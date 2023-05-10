<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Checkout extends Model
{
    use HasFactory;

    //Lấy sản phẩm trong giỏ hàng của customer, và join vs table product để lấy các thông tin cần thiết
    public function productCheckOut($id_customer)
    {
        DB::enableQueryLog();

        $getProducts = DB::table('carts')
            ->join('product', 'product.ProductID', '=', 'carts.ProductID')
            ->where('CustomerID', $id_customer)
            ->where('carts.Status', 0)
            ->get();

        return $getProducts;
    }
    //đặt hàng
    public function addCheckout($data)
    {
        return DB::table('orderproduct')->insert($data);
    }
    //update trạng thái của các sản phẩm trong giỏ hàng từ 0(chưa được đặt hàng) lên thành 1(đã được đặt)
    public function updateStatusCart($filters = [],$id_customer)
    {
        DB::enableQueryLog();
        $updateStatus = DB::table('carts');

        if (!empty($filters)) {
            $updateStatus = $updateStatus->where($filters);
        }

        $updateStatus = $updateStatus->where('CustomerID', $id_customer);
        $updateStatus = $updateStatus->update(['Status' => 1]);
        // dd(DB::getQueryLog());

        return $updateStatus;
    }
    //thêm dữ liệu vào orderdetail
    public function addOrderDetail($data)
    {
        return DB::table('orderdetail')->insert($data);
    }
    //Lấy ra OrderID của bảng orderproduct 
    public function selectOderID()
    {
        return DB::table('orderproduct')
        ->orderByDesc('OrderDate')
        ->limit(1)
        ->select('OrderID')
        ->first()->OrderID;
    }
}
