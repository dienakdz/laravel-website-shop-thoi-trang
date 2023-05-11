<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Products;

class DetailProductController extends Controller
{
    private $getDetailProduct;

    private $getRatingProduct;

    private $getAverageRating;

    private $getCountRating;

    private $postRating;
    public function __construct()
    {
        $this->getDetailProduct = new Products();
        $this->getRatingProduct = new Products();
        $this->getAverageRating = new Products();
        $this->getCountRating = new Products();
        $this->postRating = new Products();
    }
    public function index($id = 0)
    {
        $title = 'Chi tiết sản phẩm';
        $detailProduct = $this->getDetailProduct->getDetailProduct($id);

        $ratingProduct = $this->getRatingProduct->getRatingProduct($id);

        $averageRating = $this->getAverageRating->getAverageRating($id);
        $countRating = $this->getCountRating->getCountRating($id);
        // dd($ratingProduct);

        return view('clients/detail-product',compact('title', 'detailProduct','ratingProduct','averageRating','countRating'));
    }
    public function postRating(Request $request, $id)
    {
        $dataInsert = [
            'RatingComment' => $request->comment,
            'RatingName' => $request->name,
            'RatingEmail' => $request->email,
            'RatingStar' => $request->ratingstar,
            'ProductID' => $id

        ];
        // dd($dataInsert);
        $this->postRating->postRating($dataInsert);
        return redirect()->route('detail-product',['id' => $id] );
    }

    
}


