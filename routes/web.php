<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/home/{to}', [App\Http\Controllers\HomeController::class, 'index1']);
Route::get('/m', function () {


    $SERVER_API_KEY = 'AAAAPAwRvNE:APA91bHWlobs3PtIJ3iKiz9Qh7GzRD9A4ncAskWNcmOJiGthi8MiA98LS8vT42DCUEEqbOzUCFEvlZq9qoe6Fl4nIGCxv1jIJmUhSmlVR8XrsPvnxBcGbikrdwBZkgY5rlvnRYq9HLc3';

    $token_1 ='ePC0idw-QcGKkUFPciWJbv:APA91bGZJBffjr0lF1s-nf4jR7sWUiGvgA9wJuPZXn62qnsmIac0A0kZb57zRQi7it7um6ViGQmqKbhuhwQSVXuVCylEYLkJS3E9askPP-RB1lx6OC41WlDnQvU5-5hhSJDfmbvmDodr' ;

    $data = [

        "registration_ids" => [
            $token_1
        ],

        "notification" => [

            "title" => 'aloooo',

            "body" => 'come to univecity ',

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

    dd($response);

});
