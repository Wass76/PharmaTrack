<?php

namespace App\Http\Controllers;

use App\Http\Resources\Medicine as ResourcesMedicine;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Medicine;
use \App\Http\Resources\order as OrderResouce;

class OrderController extends BaseController
{
    public function index()
    {

        $order = Order::all();
        if (!isset($order)) {
        return $this->sendError('There is no orders');
        } else
         return $this->sendResponse(OrderResouce::collection($order), 'orders retrived Successfully');
    }
    public function show($id)
    {
        $order = Medicine::find($id);
        return $this->sendResponse(new OrderResouce($order), 'This order retrived successfully');
    }
}
