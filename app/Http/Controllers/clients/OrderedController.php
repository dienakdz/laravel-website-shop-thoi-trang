<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Carts;
use App\Models\clients\Ordered;
use Illuminate\Http\Request;

class OrderedController extends Controller
{
    private $getOrdered;
    private $getCustomerID;
    private $update;
    public function __construct()
    {
        $this->getCustomerID = new Carts();
        $this->getOrdered = new Ordered();
        $this->update = new Ordered();
    }

    public function index()
    {
        $title = 'Đơn mua';
        $customerID = $this->getCustomerID->getCustomerID(session('username'));
        $Ordered = $this->getOrdered->getAllOrdered($customerID);
        $orderedDetails = [];
        foreach ($Ordered as $item) {
            $orderID = $item->OrderID;
            $orderedDetails[$orderID] = $this->getOrdered->getOrderedDetail($orderID);
        }
        return view('clients/ordered', compact('title','Ordered','orderedDetails'));

    }

    public function updateStatus($id=0)
    {
        $this->update->updateStatus($id);
        return redirect()->route('ordered');
    }
}
