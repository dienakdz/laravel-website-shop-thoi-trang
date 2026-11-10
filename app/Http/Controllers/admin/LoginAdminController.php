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
        if (session()->has('admin')) {
            return redirect()->route('admin.dashboard');
        }

        $title = 'Đăng nhập Quản trị viên - Eiser';
        return view('admin.login', compact('title'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->username;
        $password = $request->password;

        $login = $this->admin->login($username, $password);

        if ($login != null) {
            $request->session()->put('admin', $login->AdminUser);
            $request->session()->put('admin_id', $login->AdminID);
            $request->session()->put('admin_name', $login->AdminName ?? $login->AdminUser);
            $request->session()->put('admin_email', $login->AdminEmail ?? '');
            $request->session()->put('admin_img', $login->AdminImg ?? '');

            return redirect()->route('admin.dashboard')->with('msg_success', 'Đăng nhập trang quản trị thành công!');
        } else {
            return redirect()->route('admin.login')->with('msg-login', 'Tài khoản hoặc mật khẩu quản trị không chính xác!');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin', 'admin_id', 'admin_name', 'admin_email', 'admin_img']);
        return redirect()->route('admin.login')->with('msg-login', 'Đã đăng xuất phiên làm việc quản trị!');
    }
}
