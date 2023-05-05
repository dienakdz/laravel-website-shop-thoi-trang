<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function getAll()
    {
        $categories = DB::table($this->table)
        ->get();
        return $categories;
    }
}
