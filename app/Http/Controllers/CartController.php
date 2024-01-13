<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Order;
use App\Models\Medicine;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use \App\Http\Resources\cart as CartResouce;
use \App\Http\Resources\order as OrderResouce;
use Carbon\Carbon;

class CartController extends BaseController
{
    // public function __construct()
    // {
    //     $this->middleware(['auth' , 'can:access-SalesOfficer']);
    // }
    public static function send($tokens, $title, $body) // device token - title of m  - body of m
    {
    $SERVER_API_KEY = 'AAAAPAwRvNE:APA91bHWlobs3PtIJ3iKiz9Qh7GzRD9A4ncAskWNcmOJiGthi8MiA98LS8vT42DCUEEqbOzUCFEvlZq9qoe6Fl4nIGCxv1jIJmUhSmlVR8XrsPvnxBcGbikrdwBZkgY5rlvnRYq9HLc3';
    $token_1 ='ePC0idw-QcGKkUFPciWJbv:APA91bGZJBffjr0lF1s-nf4jR7sWUiGvgA9wJuPZXn62qnsmIac0A0kZb57zRQi7it7um6ViGQmqKbhuhwQSVXuVCylEYLkJS3E9askPP-RB1lx6OC41WlDnQvU5-5hhSJDfmbvmDodr' ;

        $data = [
        "to" => $token_1,
        "notification" => [
            "title" => $title,
            "body" => $body,
            "sound"=> "default" // required for sound on ios
        ],
    ];
    $dataString = json_encode($data);
    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    $response = curl_exec($ch);
    return $response;
    }

    public function allCartsForPharm()
    {
        // edit Wassem
        $cart = Cart::where('user_id' , Auth::user()->id)->get();//
        if (!isset($cart)) {
            return $this->sendError('There is no cart');
        } else
            return $this->sendResponse(CartResouce::collection($cart), 'Successfully');
    }

    public function allCartsForWarehouse()
    {
        // edit Wassem
        $cart = Cart::all();
        if (!isset($cart)) {
            return $this->sendError('There is no cart');
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
                $errors[] = [$medicine_Order->trade_name, $medicine_Order->quantity];
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
                $cart->save();}

            return $this->sendResponse([$orders, $cart], 'successfully');
        } else {
            return $this->sendError('sorry, we have entered the wrong medicine quantity ', $errors);
        }
    }
    public function showForPharm($id)
    {
        $cart = Cart::find($id);
        if($cart->user_id == Auth::user()->id){
            $orders = Order::where('cart_id', $cart['id'])->get();
            return $this->sendResponse([new CartResouce($cart), OrderResouce::collection($orders)],
             'This cart with it\'s orders retrived successfully');
        }
        else{
            return $this->sendError('you don\'t have permission' ,'' ,403);
        }
    }

    public function showForWarehouse($id)
    {
        $cart = Cart::find($id);
        // if($cart->user_id == Auth::user()->id){
            $orders = Order::where('cart_id', $cart['id'])->get();
            return $this->sendResponse([new CartResouce($cart), OrderResouce::collection($orders)],
             'This cart with it\'s orders retrived successfully');
        // }
        // else{
        //     return $this->sendError('you don\'t have permission' ,'' ,403);
        // }
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

        $user_id = $cart->user_id;
        // dd($user_id);
        $user = User::find($user_id);
        $tokens = $user->Fcm_token;
        $title = 'new messege';
        $body = 'an update on your order';
        // dd($tokens);
        $notification=$this->send($tokens, $title, $body);
//        dd($notification);
        return $this->sendResponse($cart, 'successfully');

    }

    public function GetLast4Carts(){
        $carts =Cart::where('user_id' , Auth::user()->id)->orderby('created_at' , 'DESC')->take(4)->get(); // or limit()
        if(empty($carts)){
            // return $this -> sendError('no')
        }
        return $this->sendResponse($carts,'done');
    }


}
