<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Products extends Model
{
    use HasFactory;

    protected $table = 'product';

    public function getProduct($filters = [], $sorting = null, $perPage = null)
    {
        DB::enableQueryLog();

        $getProduct = DB::table('product');



        if (!empty($filters)) {
            $getProduct = $getProduct->where($filters);

        }
        if (!empty($sorting)) {
            $getProduct = $getProduct->orderBy('product.Price', $sorting);
        } else {
            $getProduct = $getProduct->inRandomOrder();
        }

        if (!empty($perPage)) {
            $getProduct = $getProduct->paginate($perPage)->withQueryString();
        } else {
            $getProduct = $getProduct->get();
        }


        // dd(DB::getQueryLog());
        return $getProduct;
    }

    public function getDetailProduct($id)
    {
        DB::enableQueryLog();

        $getDetailProduct = DB::table('product')
            ->join('categories', 'product.CategoryID', '=', 'categories.CategoryID')
            ->join('brand', 'product.BrandID', '=', 'brand.BrandID')
            ->where('ProductID', $id)->first();



        // dd(DB::getQueryLog());
        return $getDetailProduct;
    }

    public function getRatingProduct($id)
    {
        DB::enableQueryLog();

        $getRatingProduct = DB::table('rating')
            ->where('ProductID', $id)
            ->orderByDesc('rating.RatingID')
            ->paginate(2)
            ->withQueryString();

        // dd(DB::getQueryLog());
        return $getRatingProduct;
    }

    public function getAverageRating($id)
    {
        DB::enableQueryLog();

        $averageRating = DB::table('rating')
            ->where('ProductID', $id)
            ->avg('ratingstar');

        // dd(DB::getQueryLog());
        return $averageRating;
    }

    public function getCountRating($id)
    {
        DB::enableQueryLog();

        $countRating = DB::table('rating')
            ->where('ProductID', $id)
            ->count('ratingstar');

        // dd(DB::getQueryLog());
        return $countRating;
    }

    public function postRating($data)
    {
        return DB::table('rating')->insert($data);

    }
}