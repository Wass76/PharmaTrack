<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Auth;
use Validator;

class FavoriteController extends BaseController
{
    public function index(){
        $favorites = Favorite::where('user_id' , Auth::user()->id)->where('is_favorite' , true)->get();
        // dd($favorites);
        return $this->sendResponse(FavoriteResource::collection($favorites) ,'success');
    }

    public function store(Request $request){
        $input = $request->all();

        // $favorites = Favorite::where('medicine_id' , $input['medecine_name']);
        // // if(is_null($favorites)){
        // //     return $this->sendError('no such this data');
        // // }
        // foreach($favorites as $fav){
        //     if($fav->medicines()->trade_name == $input['medecine_name'])
        // }

        $validator = Validator::make($input,[
            'medecine_name' => 'required',
            'is_favorite' =>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('missed data' , $validator->errors());
        }
        $medicine = Medicine::where('trade_name' , $input['medecine_name'])->first();
        // $m = $medicine;

        // dd($m);
        if(is_null($medicine)){
            return $this->sendError('no such this data');
        }
        if($input['is_favorite'] == false){
            return $this->sendError('can\'t saved unfavorite item');
        }
        $favorite = Favorite::create([
             'user_id' => Auth::user()->id,
            'medicine_id' => $medicine->id,
            'is_favorite' => $input['is_favorite'],
        ]);

        return $this->sendResponse($favorite , 'success');
    }

    public function update(Request $request , $id){
        $favorite = Favorite::find($id);
        $input = $request->all();
        $validator = Validator::make($input,[
            'medecine_name' => 'required',
            'is_favorite' =>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('missed data' , $validator->errors());
        }
        // if($input['is_favorite'] == false)
        $favorite->is_favorite = $input['is_favorite'];
        $favorite->save();
        if($favorite->is_favorite == false){
            $favorite->delete();
        }
        return $this->sendResponse('done' , 'success');
    }
}
