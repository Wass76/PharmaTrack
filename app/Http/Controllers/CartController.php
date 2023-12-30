<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use \App\Http\Resources\cart as CartResouce;
use \App\Http\Resources\order as OrderResouce;
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
        $errors = [];
        $boolean = true;
        $input = $request->all();
        $orders = [];
        foreach ($input as $order) {
            $medicine_Order = Medicine::where('trade_name', $order['medicines_name'])->first();
            if ($medicine_Order->quantity < $order['quantity'] || $order['quantity'] <= 0) {
                $boolean = false;
                $errors[] = [$medicine_Order->scientific_name, $medicine_Order->quantity];
            }
            // $orders[] = $order;
        }
        //  dd($input);
        if ($boolean == true) {
            if (empty($input)) {

                return $this->sendError('There is no order yet');
            }
            $cart = Cart::create([
                'user_id' => Auth::id()
            ]);
            foreach ($input as $data) {
                $medicine_Order = Medicine::where('trade_name', $data['medicines_name'])->first();
                $order = Order::create([
                    'cart_id' => $cart->id,
                    'medicines_name' => $data['medicines_name'],
                    'quantity' => $data['quantity'],
                    'price' => $data['quantity'] * $medicine_Order['price'],
                ]);
                $orders[] = $order;
                $cart->Total_price  += $order->price;
                $medicine_Order->quantity = $medicine_Order->quantity - $data['quantity'];
                $medicine_Order->save();
                $cart->save();
                        }
            // dd($cart->Total_price);

            return $this->sendResponse([$orders, $cart], 'successfully');
        } else {
            return $this->sendError('sorry, we have entered the wrong medicine quantity ', $errors);
        }
    }

    public function show($id)
    {
        $cart = Cart::find($id);

        $orders = Order::where('cart_id', $cart['id'])->get();

        dd($cart->Total_price);

        return $this->sendResponse([new CartResouce($cart), OrderResouce::collection($orders)], 'This category with it\'s medicines retrived successfully');
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
        $cart->status = $request->status; // New - Preparing - Delivering - Received
        $cart->update();

        // if ($request->status == 'sent') {
        //     $orders = Order::where('cart_id', $cart['id'])->get();
        //     foreach ($orders as $order) {

        //         $medicine = Medicine::where('trade_name', $order->medicines_name)->first();
        //         $medicine->quantity = $medicine->quantity - $order['quantity'];
        //         $medicine->save();
        //     }
        // }
        return $this->sendResponse($cart, 'successfully');
    }
}
