<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Home extends Model
{
    use HasFactory;

    public function  getHomeProduct($danhmuc, $a,$b)
    {
        $homeProduct = DB::select("SELECT * FROM product WHERE ProductStatusID = $danhmuc ORDER BY UpdateDate DESC LIMIT $a, $b");
        return $homeProduct;
    }
}
