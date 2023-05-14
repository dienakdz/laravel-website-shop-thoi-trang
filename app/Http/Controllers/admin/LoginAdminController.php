<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\LoginAdmin;
use Illuminate\Http\Request;

class LoginAdminController extends Controller
{
    private $admin;

    public function __construct()
    {
        $this->admin = new LoginAdmin();
    }
    public function index()
    {
        $title = 'Đăng nhập Admin';
        return view('admin/login', compact('title'));
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        
        $login = $this->admin->login($username, $password);
        // dd($login);
        if($login != null)
        {
            $request->session()->put('admin', $request->username);
            return 'Trang quản trị!';
        }
        else {
            return redirect()->route('admin.login')->with('msg-login', 'Thông tin tài khoản hoặc mật khẩu không chính xác!');
        }
        
        
    }
}
