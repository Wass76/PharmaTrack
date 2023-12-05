<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Isset_;
use PHPUnit\Framework\Constraint\IsEmpty;
use Illuminate\Support\Facades\Validator;
use \App\Http\Resources\Category as CategoryResouce;


class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        if(!Isset($categories) ){
            return $this->sendError('There is no category yet');
        }
        else
        return $this->sendResponse(CategoryResouce::collection($categories ) , 'Categories retrived Successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input ,[
           'name' => 'required|unique:categories',
        ]);

        if($validator->fails()){
           return $this->sendError('Validate your data' , $validator->errors());
        }
        $category = Category::create($input);
        return $this->sendResponse($category , 'Adding new category done successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category= Category::find($id);
        $medicines = Medicine::where('categories_name' , $category['name'])->get();

        return $this->sendResponse([$category , $medicines ] , 'This category with it\'s medicines retrived successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function CategorySearch(Request $request){

        // if (is_numeric($id))
        // {
        //     $category = Category::find($id);
        // }
        // else
        // {
        //     $column = 'name'; // This is the name of the column you wish to search

        //     $category = Category::where($column , '=', $id)->orWhere($column , 'LIKE' ,'%' . $id . '%')->first();
        // }
        //  if(is_null($category)){
        //     return $this->sendError('no such this medicine in our wareHouse');}

        // return $this->sendResponse( new CategoryResource($category) , 'found this item successfully');

        $search = $request->get('name');
        $category = Category::where('name' , '=', $search)->orWhere('name' , 'LIKE' ,'%' . $search . '%')->get();

        if($category-> isEmpty() ) {
            return $this->sendError('no such this category in our wareHouse');
         }


        return $this->sendResponse( [$category ],   'found this item successfully');
    }
}
