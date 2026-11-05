<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Infor;
use Illuminate\Http\Request;

class InforController extends Controller
{
    private $inforModel;

    public function __construct()
    {
        $this->inforModel = new Infor();
    }

    public function index()
    {
        $title = 'Thông tin cá nhân';
        $username = session('username');
        $inforOld = $this->inforModel->selectInforCustomer($username);

        return view('clients/infor', compact('title', 'inforOld'));
    }

    public function updateInfor(Request $request)
    {
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'sdt' => 'required',
        ]);

        $updateInfor = [
            'Fullname' => $request->fullname,
            'Gender' => $request->gender ?? '',
            'Email' => $request->email,
            'Address' => $request->address ?? '',
            'Phonenumber' => $request->sdt
        ];

        $this->inforModel->updateInfor(session('username'), $updateInfor);
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

        $password = $this->inforModel->checkOldPass($username);

        if ($oldPass == $password) {
            $this->inforModel->changePassword($username, $newPass);
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
