<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Ordered extends Model
{
    use HasFactory;
    //Lấy ra danh sách các đơn hàng đã đặt của customer
    public function getAllOrdered($id_customer)
    {

        return DB::table('orderproduct')
        ->where('CustomerID', $id_customer)
        ->get();
    }

    public function getOrderedDetail($id)
    {

        return DB::table('orderproduct')
        ->join('orderdetail','orderproduct.OrderID' ,'=', 'orderdetail.OrderID')
        ->join('product', 'orderdetail.ProductID' ,'=', 'product.ProductID' )
        ->where('orderproduct.OrderID', $id)
        ->get();
    }
    //update trạng thái khi người dùng ấn đã nhận được hàng(status =4),status =3 khi đang giao đến, status = 2 là đang đợi xác nhận, còn =1 là đặt hàng thành công
    public function updateStatus($id)
    {
        return DB::table('orderproduct')
        ->where('OrderID', $id)
        ->update(['Status' => 4]);
    }
}
