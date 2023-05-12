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

        return $getProduct;
    }
    //Chi tiết sản phẩm
    public function getDetailProduct($id)
    {
        DB::enableQueryLog();

        $getDetailProduct = DB::table('product')
            ->join('categories', 'product.CategoryID', '=', 'categories.CategoryID')
            ->join('brand', 'product.BrandID', '=', 'brand.BrandID')
            ->where('ProductID', $id)->first();

        return $getDetailProduct;
    }
    //lấy ra danh sách đánh giá, mỗi trang chỉ có 2 đánh giá
    public function getRatingProduct($id)
    {
        DB::enableQueryLog();

        $getRatingProduct = DB::table('rating')
            ->where('ProductID', $id)
            ->orderByDesc('rating.RatingID')
            ->paginate(2)
            ->withQueryString();

        return $getRatingProduct;
    }
    //tính giá trị trung bình sao đánh giá của một sản phẩm
    public function getAverageRating($id)
    {
        DB::enableQueryLog();

        $averageRating = DB::table('rating')
            ->where('ProductID', $id)
            ->avg('ratingstar');

        // dd(DB::getQueryLog());
        return $averageRating;
    }
    //đếm có bao nhiêu đánh giá để hiện ra
    public function getCountRating($id)
    {
        DB::enableQueryLog();

        $countRating = DB::table('rating')
            ->where('ProductID', $id)
            ->count('ratingstar');

        // dd(DB::getQueryLog());
        return $countRating;
    }
    //đăng đánh giá
    public function postRating($data)
    {
        return DB::table('rating')->insert($data);

    }
    //update sản phẩm sau khi được đặt thành công
    public function updateQuantityProduct($id, $quantity)
    {
        return DB::table('product')
            ->where('ProductID', $id)
            ->update(['Quantity' => $quantity]);
    }

    //xử lý tìm kiếm
    public function search($searchTerm)
    {

        return DB::table('product')
            ->join('categories', 'product.CategoryID', '=', 'categories.CategoryID')
            ->join('brand', 'product.BrandID', '=', 'brand.BrandID')
            ->select('product.*','categories.CategoryName', 'brand.BrandName')
            ->where(function ($query) use ($searchTerm) {
                $query->where('ProductName', 'like', '%' . $searchTerm . '%')
                    ->orWhere('CategoryName', 'like', '%' . $searchTerm . '%')
                    ->orWhere('BrandName', 'like', '%' . $searchTerm . '%');
            })
            ->paginate(9);
    }

}