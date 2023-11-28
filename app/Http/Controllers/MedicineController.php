<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Resources\Medicine as MedicineResource;

use App\Http\Resources\Category as CategoryResource;
use Validator;
use Auth;

class MedicineController extends BaseController
{
    /**
     * Display a listing of the resource.
     */

    //  public function __construct()
    // {
    //     $this->middleware(['auth']);
    // }


    public function index()
    {
        $medicines = Medicine::all();
        // $medicines = Medicine::paginate(); // show every 15 item
        // if(Isset($medicines) ){
        //     return $this->sendError('There is no medicine yet');
        // }
        // else
            return $this->sendResponse(MedicineResource::collection($medicines) , 'all medicines retrived successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // if(Auth::user()->role_id = 1)
        // {
            $categories = Category::all();
            if($categories->count() == 0){
                return $this->sendError('Error, you have to create someCategory first' ,);
            }

         $input = $request->all();
         $validator = Validator::make($input ,[
             'scientific_name' => 'required',
             'trade_name' =>'required',
             'company_name' => 'required',
             'categories_name' =>'required',
             'quantity'=>'required',
             'expiration_at' => 'required',
             'price' =>'required',
             'form' =>'required',
             'details' => 'required'
         ]);

         if($validator->fails()){
            return $this->sendError('Validate your data' , $validator->errors());
         }
         $category = Category::where('name' , $input['categories_name'])->first();


         if(is_null($category) ){
            return $this->sendError('Sorry, we dont have this category, please validate your category name' ,);
         }
         $medicine = Medicine::create($input);
         return $this->sendResponse([$medicine , $categories ], 'Adding new item done successfully');
    //    }
    }

    public function show(Medicine $medicine)
    {
        //
    }

    public function update(Request $request, Medicine $medicine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicine $medicine)
    {
        //
    }

    public function MedicineSearch(Request $request ){

    // if (is_numeric($id))
    // {
    //     $ScMedicine = Medicine::find($id);
    // }
    // else
    // {
    //     $column = 'scientific_name'; // This is the name of the column you wish to search

    //     $ScMedicine = Medicine::where($column , '=', $id)->orWhere($column , 'LIKE' ,'%' . $id . '%')->get();
    // }


    //         $column = 'trade_name'; // This is the name of the column you wish to search

    //         $TrMedicine = Medicine::where($column , '=', $id)->orWhere($column , 'LIKE' ,'%' . $id . '%')->get();

                //  if(is_null($ScMedicine) && is_null($TrMedicine)) {
                //     return $this->sendError('no such this medicine in our wareHouse');
                //  }

        // $medicine = Medicine::find($id);
        // $input = $request->all();
        // $validator = Validator::make($input ,[
        //     'id' => 'required',
        // ]);

        $search = $request->get('name');
        $column = 'scientific_name';
        $ScMedicine = Medicine::where($column , '=', $search)->orWhere($column , 'LIKE' ,'%' . $search . '%')->get();

        $column1 = 'trade_name';
        $TrMedicine = Medicine::where($column1 , '=', $search)->orWhere($column1 , 'LIKE' ,'%' . $search . '%')->get();

        // $search1 = $request->get('trade_name');
        // $column1 = 'trade_name';
        // $TrMedicine = Medicine::where($column1 , '=', $search1)->orWhere($column1 , 'LIKE' ,'%' . $search1 . '%')->get();

        if($ScMedicine-> isEmpty() && $TrMedicine->isEmpty() ) {
            return $this->sendError('no such this medicine in our wareHouse');
         }

        // dd($ScMedicine);
        return $this->sendResponse( [$ScMedicine ,$TrMedicine ],   'found this item successfully');

    }

}
