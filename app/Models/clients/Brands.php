<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Brands extends Model
{
    use HasFactory;
    protected $table = 'brand';

    public function getAll()
    {
        $brand = DB::table($this->table)
        ->get();
        return $brand;
    }
}
