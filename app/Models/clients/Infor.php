<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Infor extends Model
{
    use HasFactory;

    //Xử lý cập nhật thông tin khách hàng
    public function updateInfor($username,$data)
    {
        return DB::table('customer')
        ->where('username', $username)
        ->update($data);
    }
    //Lấy những thông tin đã có của khách hàng trong database
    public function selectInforCustomer($username)
    {
        return DB::table('customer')
            ->where('username', $username)
            ->get();
    }

    //Tiến hành kiểm tra mật khẩu cũ, dựa vào username
    public function checkOldPass($username)
    {
        return DB::table('customer')
            ->where('username', $username)->value('password');

    }

    //Xử lý đổi mật khẩu
    public function changePassword($username, $newpass)
    {
        return DB::table('customer')
            ->where('username', $username)
            ->update(['password' => $newpass]);
    }
}