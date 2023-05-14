<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LoginAdmin extends Model
{
    use HasFactory;

    public function login($username, $password)
    {
        DB::enableQueryLog();

        $admin = DB::table('admin')
        ->where('AdminUser', $username)
        ->where('AdminPass', $password)
        ->first();

        // dd(DB::getQueryLog());
        return $admin;

    }
}
