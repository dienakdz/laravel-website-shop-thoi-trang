<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Infor;
use Illuminate\Http\Request;

class InforController extends Controller
{
    private $infor_customer;
    private $changePass;

    public function __construct()
    {
        $this->infor_customer = new Infor();
        $this->changePass = new Infor();
    }
    public function index()
    {
        $title = 'Thông tin cá nhân';
        $username = session('username');
        $inforOld = $this->infor_customer->selectInforCustomer($username);
        // dd($inforOld);
        return view('clients/infor', compact('title', 'inforOld'));
    }

    public function updateInfor(Request $request)
    {
        $updateInfor = [
            'Fullname' => $request->fullname,
            'Gender' => $request->gender,
            'Email' => $request->email,
            'Address' => $request->address,
            'Phonenumber' => $request->sdt
        ];

        $this->infor_customer->updateInfor(session('username'), $updateInfor);
        return redirect()->route('information')->with('msg', 'Cập nhật thông tin thành công!');
    }

    public function changePass(Request $request)
    {
        $username = session('username');
        $oldPass = md5($request->oldpassword);
        $newPass = md5($request->newpassword);

        $password = $this->infor_customer->checkOldPass($username);

        if ($oldPass == $password) {
            $this->infor_customer->changePassword($username, $newPass);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }

    }
}