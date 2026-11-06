<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Login;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $postLogin;
    private $newUser;
    private $checkUser;

    public function __construct()
    {
        $this->postLogin = new Login();
        $this->newUser = new Login();
        $this->checkUser = new Login();
    }

    public function index()
    {
        $title = 'Đăng nhập / Đăng ký';
        return view('clients/login', compact('title'));
    }

    // Xử lý đăng ký tài khoản mới (trả về JSON đồng bộ cho AJAX)
    public function create(Request $request)
    {
        $username = trim($request->username_signup ?? '');
        $password = $request->pass_signup ?? '';

        if (empty($username) || strlen($username) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Tên đăng nhập phải có ít nhất 3 ký tự!'
            ]);
        }

        if (empty($password) || strlen($password) < 6) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu phải có ít nhất 6 ký tự!'
            ]);
        }

        $check_username = $this->checkUser->checkUser($username);

        if ($check_username == null) {
            $dataInsert = [
                'Username' => $username,
                'Password' => md5($password),
                'Fullname' => $username,
                'Gender' => 'male',
                'Status' => 1
            ];

            $this->newUser->newUser($dataInsert);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký tài khoản thành công! Bạn có thể đăng nhập ngay.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tên tài khoản này đã được sử dụng! Vui lòng chọn tên khác.',
            ]);
        }
    }

    // Xử lý đăng nhập
    public function postLogin(Request $request)
    {
        $username = trim($request->username_signin ?? '');
        $password = md5($request->pass_signin ?? '');

        $account = [
            ['customer.Username', '=', $username],
            ['customer.Password', '=', $password]
        ];

        $user = $this->postLogin->postLogin($account);

        // Xác thực tài khoản người dùng
        if ($user != null) {
            // Tạo session lưu trữ thông tin người dùng đã đăng nhập
            $request->session()->put('username', $username);
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with('msg_signin', 'Thông tin đăng nhập hoặc mật khẩu không chính xác!');
        }
    }

    // Xử lý đăng xuất
    public function getLogout(Request $request)
    {
        $request->session()->forget('username');
        return redirect()->route('home');
    }
}
