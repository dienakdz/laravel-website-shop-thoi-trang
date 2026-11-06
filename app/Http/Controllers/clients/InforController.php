<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InforController extends Controller
{
    public function index()
    {
        $title = 'Thông tin cá nhân';
        $username = session('username');

        $customer = DB::table('customer')->where('Username', $username)->first();

        if (!$customer) {
            $customer = (object)[
                'Fullname' => $username,
                'Gender' => 'male',
                'Email' => '',
                'Address' => '',
                'Phonenumber' => ''
            ];
        }

        return view('clients/infor', compact('title', 'customer'));
    }

    public function updateInfor(Request $request)
    {
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'sdt' => 'required',
        ]);

        $username = session('username');

        $updateInfor = [
            'Fullname' => $request->fullname,
            'Gender' => $request->gender ?? 'male',
            'Email' => $request->email,
            'Address' => $request->address ?? '',
            'Phonenumber' => $request->sdt
        ];

        DB::table('customer')->where('Username', $username)->update($updateInfor);

        return redirect()->route('information')->with('msg', 'Cập nhật thông tin thành công!');
    }

    public function changePass(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|min:6',
        ]);

        $username = session('username');
        $oldPass = md5($request->oldpassword);
        $newPass = md5($request->newpassword);

        $customer = DB::table('customer')->where('Username', $username)->first();

        if ($customer && $customer->Password === $oldPass) {
            DB::table('customer')->where('Username', $username)->update(['Password' => $newPass]);
            return response()->json([
                'success' => 'success',
                'message' => 'Đổi mật khẩu thành công!'
            ]);
        } else {
            return response()->json([
                'success' => 'error',
                'message' => 'Mật khẩu hiện tại không chính xác!'
            ]);
        }
    }
}
