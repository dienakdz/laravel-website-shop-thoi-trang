<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Checkout extends Model
{
    use HasFactory;

    // protected $table = 'checkout';
    public function productCheckOut($id_customer)
    {
        DB::enableQueryLog();

        $getProducts = DB::table('carts')
            ->join('product', 'product.ProductID', '=', 'carts.ProductID')
            ->where('CustomerID', $id_customer)
            ->where('carts.Status', 0)
            ->get();

        // dd(DB::getQueryLog());
        return $getProducts;
    }
    public function addCheckout($data)
    {
        return DB::table('orderproduct')->insert($data);
    }
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
}
