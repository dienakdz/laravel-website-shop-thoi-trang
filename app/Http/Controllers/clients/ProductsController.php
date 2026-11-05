<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Products;

class ProductsController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Products();
    }

    public function index(Request $request)
    {
        $title = 'Cửa hàng sản phẩm';
        $filters = [];
        $sorting = null;
        $perPage = 9;

        if (!empty($request->category)) {
            $filters[] = ['product.CategoryID', '=', $request->category];
        }

        if (!empty($request->brand)) {
            $filters[] = ['product.BrandID', '=', $request->brand];
        }

        if (!empty($request->sorting) && in_array($request->sorting, ['asc', 'desc'])) {
            $sorting = $request->sorting;
        }

        if (!empty($request->amount)) {
            $arr = explode("-", $request->amount);
            if (count($arr) == 2) {
                $amount1 = (int)str_replace([".", "đ", " "], "", trim($arr[0]));
                $amount2 = (int)str_replace([".", "đ", " "], "", trim($arr[1]));
                if ($amount1 <= 0) $amount1 = 10000;
                if ($amount2 > $amount1) {
                    $filters[] = ['product.Price', '>=', $amount1];
                    $filters[] = ['product.Price', '<=', $amount2];
                }
            }
        }

        $productList = $this->productModel->getProduct($filters, $sorting, $perPage);

        return view('clients/products', compact('title', 'productList'));
    }

    public function search(Request $request)
    {
        $title = 'Tìm kiếm sản phẩm: ' . ($request->search ?? '');
        $itemSearch = $this->productModel->search($request->search ?? '');

        return view('clients/products', compact('title', 'itemSearch'));
    }
}
