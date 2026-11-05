<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Home extends Model
{
    use HasFactory;

    protected $table = 'product';

    public function getHomeProduct($statusId, $offset = 0, $limit = 8)
    {
        return DB::table('product')
            ->where('ProductStatusID', $statusId)
            ->orderByDesc('UpdateDate')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
