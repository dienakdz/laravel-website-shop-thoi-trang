<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ordered extends Model
{
    use HasFactory;

    // Lấy ra danh sách các đơn hàng đã đặt của customer (đơn mới nhất lên đầu)
    public function getAllOrdered($id_customer)
    {
        return DB::table('orderproduct')
            ->where('CustomerID', $id_customer)
            ->orderBy('OrderID', 'desc')
            ->get();
    }

    // Lấy chi tiết các sản phẩm trong đơn hàng
    public function getOrderedDetail($id)
    {
        return DB::table('orderdetail')
            ->join('product', 'orderdetail.ProductID', '=', 'product.ProductID')
            ->where('orderdetail.OrderID', $id)
            ->select('orderdetail.*', 'product.ProductName', 'product.Picture')
            ->get();
    }

    // Khách hàng xác nhận đã nhận hàng (Status = 4)
    public function updateStatus($id)
    {
        return DB::table('orderproduct')
            ->where('OrderID', $id)
            ->update(['Status' => 4]);
    }

    // Khách hàng hủy đơn hàng (Status = 0)
    public function cancelOrder($id)
    {
        return DB::table('orderproduct')
            ->where('OrderID', $id)
            ->update(['Status' => 0]);
    }
}
