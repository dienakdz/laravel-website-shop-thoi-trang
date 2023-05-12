<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Contact extends Model
{
    use HasFactory;

    public function insert($data)
    {
        return DB::table('contact')->insert($data);
    }
}
