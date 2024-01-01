<?php

use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;



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



Route::get('/photo/{filename}', [PhotoController::class, 'show'])->name('photo.show');



Route::prefix('Pharmacy')->group(function () {

    Route::post('register', [App\Http\Controllers\AuthController::class, 'pharmacyRegister']);
    Route::post('login', [App\Http\Controllers\AuthController::class, 'pharmacyLogin']);

    Route::middleware('auth:api')->group(function () {
        //auth and user
        Route::get('userInfo/{id}', [App\Http\Controllers\UserController::class, 'showPersonalInfo']);
        Route::post('userInfo/{id}', [App\Http\Controllers\UserController::class, 'updatePesonalInfo']);
        Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);

        //browse Medicines
        Route::get('medicines', [App\Http\Controllers\MedicineController::class, 'index']);

        Route::get('medicines/{id}', [App\Http\Controllers\MedicineController::class, 'show']);


        //browse the Categories
        Route::get('category', [App\Http\Controllers\CategoryController::class, 'index']);

        Route::get('category/{id}', [App\Http\Controllers\CategoryController::class, 'show']);


        //Searching for Medicine
        Route::post('search/medicine/', [App\Http\Controllers\MedicineController::class, 'MedicineSearch']);

        Route::get('searchList/medicine/', [App\Http\Controllers\MedicineController::class, 'SearchList']);
        //Searching for Category
        Route::post('search/category/', [App\Http\Controllers\CategoryController::class, 'CategorySearch']);
        //search in category
        Route::post('search/medicineInCategory/{id}', [App\Http\Controllers\CategoryController::class, 'SearchInCategory']);

        Route::post('search/medicine/', [App\Http\Controllers\MedicineController::class, 'MedicineSearch']);
        //Searching for Category
        Route::post('search/category/', [App\Http\Controllers\CategoryController::class, 'CategorySearch']);

        //order
        Route::get('order/index/', [App\Http\Controllers\OrderController::class, 'index']);
        Route::get('order/show/{id}', [App\Http\Controllers\OrderController::class, 'show']);
        Route::post('cart/store/', [App\Http\Controllers\CartController::class, 'store']);
        Route::get('cart/index/', [App\Http\Controllers\CartController::class, 'allCartsForPharm']);
        Route::get('cart/show/{id}', [App\Http\Controllers\CartController::class, 'showForPharm']);

        Route::get('cart/LatestCarts', [App\Http\Controllers\CartController::class, 'GetLast4Carts']);

        // Favorire
        Route::post('favorite/add', [App\Http\Controllers\FavoriteController::class, 'changeStatus']);
        Route::get('favorite', [App\Http\Controllers\FavoriteController::class, 'index']);

        Route::get('report' , [App\Http\Controllers\ReportController::class , 'CartReport']);



    });
});





Route::prefix('WareHouse')->group(function () {

    //Signing
    Route::post('login', [App\Http\Controllers\AuthController::class, 'WareHouseLogin']);
    Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);

    Route::middleware('auth:api')->group(function () {

        //browse Medicines
        Route::get('medicines', [App\Http\Controllers\MedicineController::class, 'index']);

        //browse Categories
        Route::get('category', [App\Http\Controllers\CategoryController::class, 'index']);

        //   show some category and it's medicines
        Route::get('category/{id}', [App\Http\Controllers\CategoryController::class, 'show']);

        //   show Medicines
        Route::get('medicines/{id}', [App\Http\Controllers\MedicineController::class, 'show']);


        //Searching for Medicine
        Route::post('search/medicine/', [App\Http\Controllers\MedicineController::class, 'MedicineSearch']);
        //Searching for Category
        Route::post('search/category/', [App\Http\Controllers\CategoryController::class, 'CategorySearch']);

        //Products Sales Report
        Route::get('SalesReport' , [App\Http\Controllers\ReportController::class , 'productSalesReportForMonth']);

          //users Report
        Route::get('UsersReport' ,[App\Http\Controllers\ReportController::class , 'BestUsersForMonth'] );

        //Adding new Medicine or Category
        Route::middleware(['can:access-Secretary'])->group(function () {

            //Adding new Medicine or Category
            Route::post('medicines', [App\Http\Controllers\MedicineController::class, 'store']);
            Route::post('category', [App\Http\Controllers\CategoryController::class, 'store']);


            //Updating data for Medicine or Category
            Route::post('medicines/{id}', [App\Http\Controllers\MedicineController::class, 'update']);
            Route::post('category/{id}', [App\Http\Controllers\CategoryController::class, 'update']);
            //Searching for Medicine
            Route::post('search/medicine/', [App\Http\Controllers\MedicineController::class, 'MedicineSearch']);
            //Searching for Category
            Route::post('search/category/', [App\Http\Controllers\CategoryController::class, 'CategorySearch']);
            //delete
            Route::get('medicines/destroy/{id}', [App\Http\Controllers\MedicineController::class, 'destroy']);
        });

        //Showing Pharmacies data
        Route::middleware(['can:access-SalesOfficer'])->group(function () {
            //get pharmacies
            Route::get('users', [App\Http\Controllers\UserController::class, 'index']);
            //get some order details
            Route::get('order/show/{id}', [App\Http\Controllers\OrderController::class, 'show']);
            //get all orders
            Route::get('order/index/', [App\Http\Controllers\OrderController::class, 'index']);
            //get some cart info
            Route::get('cart/{id}', [App\Http\Controllers\CartController::class, 'showForWarehouse']);
            //get all of my carts
            Route::get('carts', [App\Http\Controllers\CartController::class, 'allCartsForWarehouse']);
            //update cart's info
            Route::post('cart/{id}', [App\Http\Controllers\CartController::class, 'update']);
        });
    });
});
