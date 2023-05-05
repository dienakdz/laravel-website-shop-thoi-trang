<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Carts extends Model
{
    use HasFactory;
    protected $table = 'carts';
    public function getCarts($id_custormer)
    {
        DB::enableQueryLog();

        $getCarts = DB::table('carts')
            ->join('product', 'product.ProductID', '=', 'carts.ProductID')
            ->where('CustomerID', $id_custormer)
            ->where('Status', 0)
            ->get();

        // dd(DB::getQueryLog());
        return $getCarts;
    }

    public function addCart($data)
    {
        DB::enableQueryLog();

        $addCart = DB::table('carts')->insert($data);

        // dd(DB::getQueryLog());
        return $addCart;
    }

    public function updateCart($customerID, $productID, $cart)
    {
        DB::enableQueryLog();

        $updateCart = DB::table('carts')
            ->where('CustomerID', $customerID)
            ->where('ProductID', $productID)
            ->update($cart);

        // dd(DB::getQueryLog());
        return $updateCart;
    }

    public function getCustomerID($username)
    {
        return DB::table('customer')->where('Username', $username)->value('CustomerID');

    }
    public function getProductCustomer($CustomerID, $ProductID)
    {
        return DB::table('carts')->where('CustomerID', $CustomerID)->where('ProductID', $ProductID)->get();
    }

    public function updateQuantity($customerID, $productID, $quantity)
    {        
        $updateQuantity = DB::table('carts')
            ->where('CustomerID', $customerID)
            ->where('ProductID', $productID)
            ->update($quantity);

        // dd(DB::getQueryLog());
        return $updateQuantity;
    }

    public function getItemProduct($CustomerID, $ProductID)
    {
        return DB::table('carts')
        ->join('product', 'product.ProductID', '=', 'carts.ProductID')
        ->where('carts.CustomerID', $CustomerID)
        ->where('carts.ProductID', $ProductID)
        ->get();
    }

    public function deleteProductCart($id_cart)
    {
        return DB::table('carts')
        ->where('CartID', '=', $id_cart)->delete();
        
    }
}