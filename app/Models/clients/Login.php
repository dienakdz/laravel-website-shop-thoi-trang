<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Login extends Model
{
    use HasFactory;

    protected $table = 'customer';

    public function postLogin($account)
    {
        DB::enableQueryLog();

        $getUser = DB::table('customer')
        ->where($account)
        ->first();

        // dd(DB::getQueryLog());
        return $getUser;
    }

    public function newUser($data)
    {
        return DB::table('customer')->insert($data);
    }
    public function checkUser($username)
    {
        DB::enableQueryLog();

        $checkUser = DB::table('customer')
            ->where('Username', $username)
            ->first();


        // dd(DB::getQueryLog());
        return $checkUser;
    }
}