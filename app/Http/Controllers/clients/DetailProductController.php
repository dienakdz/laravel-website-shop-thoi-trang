<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Products;

class DetailProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Products();
    }

    public function index($id = 0)
    {
        $detailProduct = $this->productModel->getDetailProduct($id);

        if (!$detailProduct) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        $title = $detailProduct->ProductName;
        $ratingProduct = $this->productModel->getRatingProduct($id);
        $averageRating = round($this->productModel->getAverageRating($id) ?? 5, 1);
        $countRating = $this->productModel->getCountRating($id) ?? 0;

        return view('clients/detail-product', compact('title', 'detailProduct', 'ratingProduct', 'averageRating', 'countRating'));
    }

    public function postRating(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required',
            'name' => 'required',
            'ratingstar' => 'required|numeric|min:1|max:5',
        ]);

        $dataInsert = [
            'RatingComment' => $request->comment,
            'RatingName' => $request->name,
            'RatingEmail' => $request->email ?? '',
            'RatingStar' => $request->ratingstar,
            'ProductID' => $id,
        ];

        $this->productModel->postRating($dataInsert);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đánh giá sản phẩm thành công!'
            ]);
        }

        return redirect()->route('detail-product', ['id' => $id])->with('msg_rating', 'Đánh giá thành công!');
    }
}
