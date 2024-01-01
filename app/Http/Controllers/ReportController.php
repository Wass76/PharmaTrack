<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Order;
use App\Models\User;
use App\Models\Medicine;
use Carbon\Carbon;
use Auth;

use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function productSalesReportForMonth()
    {

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $medicines = Medicine::all(); // Retrieve all products
        $reportData = [];

        foreach ($medicines as $product) {
            $totalSales = Order::where('medicines_name', $product->trade_name)->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');
            $reportData[] = [
                'product_name' => $product->trade_name,
                'total_sales' => $totalSales
            ];
        }

        usort($reportData, function ($a, $b) {
            return $b['total_sales'] - $a['total_sales'];
        });
        return $this->sendResponse($reportData, 'Sales Report retrieved successfully');
    }


    public function BestUsersForMonth()
    {

        // $date = now()->month();
        $startDate = Carbon::now() ->startOfMonth();
        // dd($startDate);
        $endDate = Carbon::now()->endOfMonth();
        // dd($startDate , $endDate);

        $users = User::where('role_id', 1)->get();

            foreach ($users as $user) {

                $totalSales = cart::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])
                ->sum('Total_price');

                $reportData[] = [
                    'user_name' => $user->name,
                    'total_sales' => $totalSales
                ];
            }
        usort($reportData, function ($a, $b) {
            return $b['total_sales'] - $a['total_sales'];
        });

        // $reportData->orderby('total_sales' ,'DESC');
        return $this->sendResponse($reportData, 'success');
    }


    public function OrderReportForMonth()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $medicines = Medicine::all(); // Retrieve all products
        $reportData = [];

        // foreach ($medicines as $product) {
            $totalOrder = cart::where('user_id', Auth::user()->id)->whereBetween('created_at', [$startDate, $endDate])
            ->sum('Total_price');
            // dd($totalOrder);
            $reportData[] = [
                'total_sales' => $totalOrder
            ];
        // }

        // usort($totalOrder, function ($a, $b) {
        //     return $b['total_sales'] - $a['total_sales'];
        // });
        return $this->sendResponse($reportData, 'Sales Report retrieved successfully');
    }



    public function MostPriceOrdersForWarehouse()
    {

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $medicines = Medicine::all(); // Retrieve all products
        $reportData = [];

        $carts = cart::whereBetween('created_at', [$startDate, $endDate])
        ->orderby('Total_price' ,'DESC')->take(3)->get();

        return $this->sendResponse($carts, 'Sales Report retrieved successfully');
    }


    public function MostPriceOrdersForPharm()
    {

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $medicines = Medicine::all(); // Retrieve all products
        $reportData = [];

        $carts = cart::where('user_id' , Auth::user()->id)->whereBetween('created_at', [$startDate, $endDate])
        ->orderby('Total_price' ,'DESC')->get();

        $totalOrder = cart::where('user_id', Auth::user()->id)->whereBetween('created_at', [$startDate, $endDate])
        ->sum('Total_price');
        $reportData[] = [
            'total_sales' => $totalOrder
        ];

        return $this->sendResponse([$carts ,$reportData], 'Sales Report retrieved successfully');
    }

    public function CartReport()
    {

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $medicines = Medicine::all(); // Retrieve all products
        $reportData = [];

        $carts = cart::where('user_id' , Auth::user()->id)->whereBetween('created_at', [$startDate, $endDate])
        ->orderby('created_at' ,'ASC')->get();

        $totalOrder = cart::where('user_id', Auth::user()->id)->whereBetween('created_at', [$startDate, $endDate])
        ->sum('Total_price');
        $reportData[] = [
            'total_sales' => $totalOrder
        ];

        return $this->sendResponse([$carts ,$reportData], 'Sales Report retrieved successfully');
    }






    // public function BestOrderReport(){
    //     $carts = Cart::orderby('Total_price' , 'ASC');
    //     $reportData = [];

    // foreach ($carts as $cart) {
    //     $totalSales = Order::where('product_id', $cart->id)->sum('quantity');
    //     $reportData[] = [
    //         'user_name' => $cart->users()->name,
    //         'total_sales' => $totalSales
    //     ];
    // }
    //     return $this->sendResponse($reportData , 'Sales Report retrieved successfully');
    //     }
}
