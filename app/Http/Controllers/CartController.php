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

            $orders[] = $order;
            $medicine_Order  = Medicine::where('scientific_name', $order['medicines_name'])->first();

            if ($medicine_Order->quantity < $order['quantity'] || $order['quantity'] <= 0 ) {
                $cart->Delete();
               $order->delete();
               foreach ($orders as $order)
               {$order->delete();}
               foreach($order as $order){
                return $this->sendError('sorry, we have entered the wrong medicine quantity ', [$medicine_Order->scientific_name, $medicine_Order->quantity]);

               }
            }

        }
         if(empty($orders) ){
            $cart->delete();
            return $this->sendError('There is no order yet');
         }
        return $this->sendResponse([$orders, $cart],  'successfully');
    }
    //
    public function show($id)
    {
        $cart= Cart::find($id);
        if(!Isset($cart) ){
            return $this->sendError('unavailable');
        }
        $orders = Order::where('cart_id' , $cart['id'])->get();

        return $this->sendResponse([new CartResouce($cart) ,  OrderResouce::collection($orders)] , 'This category with it\'s medicines retrived successfully');
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
