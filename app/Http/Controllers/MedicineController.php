<?php

namespace App\Http\Controllers;

use App\Http\Resources\medicinesNames;
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
        //$medicines = Medicine::paginate(); // show every 15 item
        if(!Isset($medicines) ){
            return $this->sendError('There is no medicine yet');
        }
        else{
            return $this->sendResponse($medicines , 'all medicines retrived successfully');}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // if(Auth::user()->role_id = 1)
        // {
        $categories = Category::all();
        if ($categories->count() == 0) {
            return $this->sendError('Error, you have to create someCategory first',);
        }

         $input = $request->all();
         $validator = Validator::make($input ,[
             'scientific_name' => ['required' , 'unique:Medicines,scientific_name'],
             'trade_name' =>['required' , 'unique:Medicines,trade_name'],
             'company_name' => 'required',
             'photo' =>  'image',
             'categories_name' =>'required',
             'quantity'=>['required'],
             'expiration_at' => ['required'],
             'price' =>'required',
             'form' =>'required',
             'details' => 'required'
         ]);

         if($validator->fails()){
            return $this->sendError('Validate your data' , $validator->errors());
         }
         if($input['quantity'] <= 0){
            return $this->sendError('your quantity\'s medicines should be at least 1');
         }
         $category = Category::where('name' , $input['categories_name'])->first();


         if(is_null($category) ){
            return $this->sendError('Sorry, we dont have this category, please validate your category name' ,);
         }

         $photo = $request->photo;
         $newPhoto = time().$photo->getClientOriginalName();
         $photo->move('uploads/medicines',$newPhoto);

         $medicine = Medicine::create([
                'scientific_name' => $request->scientific_name ,
                'trade_name'      => $request->trade_name ,
                'company_name'    => $request->company_name ,
                'photo'           =>  'uploads/medicines'.$newPhoto ,
                'categories_name' => $request-> categories_name ,
                'quantity'        => $request->quantity ,
                'expiration_at'   => $request->expiration_at ,
                'price'           => $request-> price ,
                'form'            => $request-> form,
                'details'         => $request->details,
         ]);
         return $this->sendResponse([$category,$medicine ], 'Adding new item done successfully');
    }

    public function show($id)
    {
        // $medicine = Medicine::where('scientific_name' , $request->scientific_name)->first();
        $medicine = Medicine::find($id);
        return $this->sendResponse(new MedicineResource($medicine) , 'This medicine retrived successfully');
    }

    public function update(Request $request ,$id)
    {
        $medicine = Medicine::find($id);
        $validator = Validator::make($request->all() ,[
            'scientific_name' => 'required',
            'trade_name' =>'required',
            'company_name' => 'required',
            'photo' =>  'required|image',
            'categories_name' =>'required',
            'quantity'=>'required',
            'expiration_at' => 'required',
            'price' =>'required',
            'form' =>'required',
            'details' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('validate your data,' , $validator->errors());
        }

        if ($request->has('photo')) {
            $photo = $request->photo;
            $newPhoto = time().$photo->getClientOriginalName();
            $photo->move('uploads/medicines',$newPhoto);
            $medicine->photo ='uploads/medicines/'.$newPhoto ;
        }
          $medicine->scientific_name = $request->scientific_name ;
          $medicine->trade_name = $request->trade_name ;
          $medicine->company_name =$request->company_name ;
          $medicine->categories_name =$request-> categories_name ;
          $medicine->quantity =$request->quantity ;
          $medicine->expiration_at =$request->expiration_at ;
          $medicine->price =$request-> price ;
          $medicine->form =$request-> form;
          $medicine->details =$request->details;

          $medicine->save();
          return $this->sendResponse(new MedicineResource($medicine) ,  'updated data done successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Medicine $medicine)
    {
        if($medicine->quantity <= 0){
            $medicine->delete();
            return $this->sendResponse('Done' , 'Data deleted successfully');
        }
    }

    public function HDelete($id){
        $medicine = Medicine::find($id);
        if(!Isset($medicine)){
            return $this->sendError('The item could not be found' , );
        }
        $medicine->forceDelete();
        return $this->sendResponse('Done' , 'Data Deleted Permanently');
        }

    public function MedicineSearch(Request $request ){

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
        $ScMedicine = Medicine::where($column, '=', $search)->orWhere($column, 'LIKE', '%' . $search . '%')->get();

        $column1 = 'trade_name';
        $TrMedicine = Medicine::where($column1, '=', $search)->orWhere($column1, 'LIKE', '%' . $search . '%')->get();

        // $search1 = $request->get('trade_name');
        // $column1 = 'trade_name';
        // $TrMedicine = Medicine::where($column1 , '=', $search1)->orWhere($column1 , 'LIKE' ,'%' . $search1 . '%')->get();

        if($ScMedicine-> isEmpty() && $TrMedicine->isEmpty() ) {
            return $this->sendError('No such this medicine in our wareHouse');
         }

        // dd($ScMedicine);
        return $this->sendResponse( [$ScMedicine ,$TrMedicine ],   'found this item successfully');
    }
    public function SearchList(){
        //pluck
        $Scmedicines = Medicine::all();
        return $this->sendResponse(MedicinesNames::collection($Scmedicines) , 'all data names retrived successfully');
    }
}
