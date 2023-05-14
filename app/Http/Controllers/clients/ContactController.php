<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use DateTimeZone;
use Illuminate\Http\Request;
use App\Models\clients\Contact;
use Nette\Utils\DateTime;
class ContactController extends Controller
{
    private $insertContact;
    public function __construct()
    {
        $this->insertContact = new Contact();
    }

    public function index()
    {
        $title = 'Liên hệ';
        return view('clients/contact',compact('title'));
    }

    public function postContact(Request $request)
    {
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
        
        $contact = [
            'FullName' => $request->name,
            'Email' => $request->email,
            'Subject' => $request->subject,
            'Content' => $request->message,
            'ContactDate' => $date->format('Y-m-d H:i:s'),
            'Status' => 0
        ];
        // dd($contact);
        $this->insertContact->insert($contact);

        return response()->json(['success' => true]);
    }
    
}
