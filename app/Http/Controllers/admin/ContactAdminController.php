<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactAdminController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hòm thư Liên hệ & Phản hồi - DDien Admin';

        $totalContacts = DB::table('contact')->count();
        $unreadContacts = DB::table('contact')->where('Status', '0')->count();
        $readContacts = DB::table('contact')->where('Status', '1')->count();

        $query = DB::table('contact');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('Status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->orWhere('FullName', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhere('Title', 'like', "%{$search}%")
                  ->orWhere('Content', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('ContactID', 'desc')->paginate(10)->withQueryString();

        return view('admin.contacts.index', compact(
            'title',
            'contacts',
            'totalContacts',
            'unreadContacts',
            'readContacts'
        ));
    }

    public function markRead($id)
    {
        $contact = DB::table('contact')->where('ContactID', $id)->first();
        if (!$contact) {
            return back()->with('msg_error', 'Không tìm thấy tin nhắn liên hệ!');
        }

        $newStatus = ($contact->Status == '1') ? '0' : '1';
        DB::table('contact')->where('ContactID', $id)->update(['Status' => $newStatus]);

        $statusText = ($newStatus == '1') ? 'Đã xử lý / Đã đọc' : 'Chưa xử lý';
        return back()->with('msg_success', "Đã chuyển trạng thái tin nhắn #LH-{$id} sang: {$statusText}.");
    }

    public function destroy($id)
    {
        DB::table('contact')->where('ContactID', $id)->delete();
        return back()->with('msg_success', "Đã xóa tin nhắn liên hệ #LH-{$id} thành công.");
    }
}
