<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\clients\Home;
use DB;

class HomeController extends Controller
{
    private $homeProduct;

    public function __construct()
    {
        $this->homeProduct = new Home();
    }
    public function index()
    {
        $banner = DB::select('select * from banner');
        $title = 'Trang chủ';
        $featuredProductList =  $this->homeProduct->getHomeProduct(2,0,3);
        $newProductList =  $this->homeProduct->getHomeProduct(1,0,4);
        $discountProductList = $this->homeProduct->getHomeProduct(3,0,8);
        return view('clients/home',compact('title','banner','featuredProductList','newProductList','discountProductList'));
    }


    //Hien thi form them du lieu phuong thuc GET
    public function create()
    {
        //
    }


    //Xu ly them du lieu phuong thuc POST
    public function store(Request $request)
    {
        //
    }

    //Lay ra thong tin cua 1 du lieu Phuong thuc GET
    public function show($id)
    {
        //
    }

    //Hien thi form sua du lieu phuong thuc GET
    public function edit($id)
    {
        //
    }

    //Xu ly sua san pham 
    public function update(Request $request, $id)
    {
        //
    }

    //Xoa san pham
    public function destroy($id)
    {
        //
    }
}
