<?php

use App\Models\clients\Categories;
use App\Models\clients\Brands;
use Illuminate\Support\Facades\DB;

function getAllCategories()
{
    $categories = new Categories;
    return $categories->getAll();
}

function getAllBrands()
{
    $brand = new Brands;
    return $brand->getAll();
}

function getCartQuantity()
{
    if (session()->has('username')) {
        $customerId = DB::table('customer')->where('Username', session('username'))->value('CustomerID');
        if ($customerId) {
            return (int) DB::table('carts')->where('CustomerID', $customerId)->where('Status', 0)->sum('CartQuantity');
        }
    }
    return 0;
}
