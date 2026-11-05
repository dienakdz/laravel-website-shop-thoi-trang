<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Checkout extends Model
{
    use HasFactory;

    // Lấy sản phẩm trong giỏ hàng của customer (Status = 0: chưa đặt)
    public function productCheckOut($id_customer)
    {
        return DB::table('carts')
            ->join('product', 'product.ProductID', '=', 'carts.ProductID')
            ->where('CustomerID', $id_customer)
            ->where('carts.Status', 0)
            ->select('carts.*', 'product.ProductName', 'product.Price', 'product.Picture', 'product.Quantity')
            ->get();
    }

    // Đặt hàng và trả về OrderID vừa tạo
    public function addCheckout($data)
    {
        return DB::table('orderproduct')->insertGetId($data);
    }

    // Cập nhật trạng thái các sản phẩm trong giỏ hàng thành đã đặt (Status = 1)
    public function updateStatusCart($filters = [], $id_customer)
    {
        $query = DB::table('carts')->where('CustomerID', $id_customer);

        if (!empty($filters)) {
            $query = $query->where($filters);
        }

        return $query->update(['Status' => 1]);
    }

    // Thêm dữ liệu vào orderdetail
    public function addOrderDetail($data)
    {
        return DB::table('orderdetail')->insert($data);
    }

    // Hàm dự phòng lấy OrderID mới nhất (giữ để tương thích)
    public function selectOderID()
    {
        $order = DB::table('orderproduct')
            ->orderByDesc('OrderID')
            ->first();

        return $order ? $order->OrderID : null;
    }
}
