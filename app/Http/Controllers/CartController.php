<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use \App\Http\Resources\cart as CartResouce;
use Carbon\Carbon;

class CartController extends BaseController
{

    public function index()
    {
        $cart = Cart::all();
        if (!isset($cart)) {
            return $this->sendError('There is no carts');
        } else
            return $this->sendResponse(CartResouce::collection($cart), 'Successfully');
    }


    public function store(Request $request)
    {

        $cart = Cart::create([
            'user_id' => Auth::id()
        ]);
        $input = $request->all();
        $orders = [];
        foreach ($input as $order) {
            $order = Order::create([
                'cart_id' => $cart->id,
                'medicines_name' => $order['medicines_name'],
                'quantity' => $order['quantity'],
            ]);
            $medicine_Order  = Medicine::where('scientific_name', $order['medicines_name'])->first();


            if ($medicine_Order->quantity < $order['quantity']) {
                return $this->sendError('sorry, we dont have this quantity from this medicine', [$medicine_Order->scientific_name, $medicine_Order->quantity]);
            }
            $orders[] = $order;
        }



        return $this->sendResponse([$orders, $cart],  'successfully');
    }
    //
    public function show($id)
    {
        $medicine = Medicine::find($id);
        return $this->sendResponse(new CartResouce($medicine), 'This medicine retrived successfully');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $Validator = Validator::make($input, [
            'paid_status' => 'required',
            'status' => 'required'
        ]);
        $cart = Cart::find($id);
        $cart->paid_status = $request->paid_status;
        $cart->status = $request->status;
        $cart->update();
        if ($request->status == 'sent') {
            $orders = Order::where('cart_id',  $cart['id'])->get();
            foreach ($orders as $order) {

                $medicine = Medicine::where('scientific_name', $order->medicines_name)->first();
                $medicine->quantity = $medicine->quantity - $order['quantity'];
                $medicine->save();
            }
        }
        return $this->sendResponse($cart, 'successfully');
    }
}
