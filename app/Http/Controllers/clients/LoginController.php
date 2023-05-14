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
        $title = 'Đăng nhập';
        return view('clients/login', compact('title'));
    }


    public function create(Request $request)
    {
        $check_username = $this->checkUser->checkUser($request->username_signup);
        if ($check_username == null) {
            $dataInsert = [
                'Username' => $request->username_signup,
                'Password' => md5($request->pass_signup),
                'Status' => 1

            ];
            // dd($dataInsert);
            $this->newUser->newUser($dataInsert);
            // return redirect()->route('login')->with('msg_signup', 'Đăng kí thành công!');
            return response()->json([
                'success' => true,
                'message' => 'Đăng kí thành công!',
            ]);
        } else {
            return redirect()->route('login')->with('msg_signup', 'Tên tài khoản đã tồn tại!');
        }

    }

    public function postLogin(Request $request)
    {
        $account = [];

        $username = $request->username_signin;
        $password = md5($request->pass_signin);
        $account[] = ['customer.Username', '=', $username];
        $account[] = ['customer.Password', '=', $password];

        $user = $this->postLogin->postLogin($account);

        // Xác thực tài khoản người dùng
        if ($user != null) {
            // Tạo session lưu trữ thông tin người dùng đã đăng nhập
            $request->session()->put('username', $request->username_signin);
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with('msg_signin', 'Thông tin đăng nhập không chính xác!');
        }
    }

    public function getLogout(Request $request)
    {
        // Xóa session lưu trữ thông tin người dùng đã đăng nhập
        $request->session()->forget('username');
        return redirect()->route('home');
    }

}