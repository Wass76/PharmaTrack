<?php

 use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('Pharmacy')->group(function () {
    //Signing
    Route::post('register' ,[App\Http\Controllers\AuthController::class ,'pharmacyRegister']);
    Route::post('login' ,[App\Http\Controllers\AuthController::class , 'pharmacyLogin']);
    Route::get('logout' ,[App\Http\Controllers\AuthController::class , 'logout']);



    Route::middleware('auth:api')->group(function(){

    //browse the medicines
        Route::get('medicines' ,[App\Http\Controllers\MedicineController::class , 'index']);

        //Searching for Medicine
        Route::post('search/medicine/' ,[App\Http\Controllers\MedicineController::class , 'MedicineSearch']);
         //Searching for Category
        Route::post('search/category/' ,[App\Http\Controllers\MedicineController::class , 'CategorySearch']);
    });
});


Route::prefix('WareHouse')->group(function () {

    //Signing
    Route::post('login' ,[App\Http\Controllers\AuthController::class , 'WareHouseLogin']);
    Route::get('logout' ,[App\Http\Controllers\AuthController::class , 'logout']);

    Route::middleware('auth:api')->group(function(){

        Route::get('medicines' ,[App\Http\Controllers\MedicineController::class , 'index']);

    //Searching for Medicine
        Route::post('search/medicine/' ,[App\Http\Controllers\MedicineController::class , 'MedicineSearch']);
    //Searching for Category
        Route::post('search/category/' ,[App\Http\Controllers\MedicineController::class , 'CategorySearch']);

    //Adding new Medicine or Category
        Route::middleware(['can:access-Secretary'])->group(function(){
            Route::post('medicines' ,[App\Http\Controllers\MedicineController::class , 'store']);
            Route::post('category' ,[App\Http\Controllers\CategoryController::class , 'store']);
        });

        //Showing Pharmacies data
        Route::middleware(['can:access-SalesOfficer'])->group(function(){
            Route::get('users' ,[App\Http\Controllers\UserController::class , 'index']);
        });

    });

});


