<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavoriteResource;
use App\Http\Resources\Medicine as MedicineResourcce;

use App\Models\Favorite;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Auth;
use Validator;


class FavoriteController extends BaseController
{
    public function index()
    {
        $FMedicines = [];

        $favorites = Favorite::where('user_id', Auth::user()->id)->where('is_favorite', true)->get();
        // dd($favorites);
        foreach($favorites as $favorite){

            $medicine = Medicine::where('id' , $favorite['medicine_id'])->get();
            $FMedicines []= $medicine;
            // dd($FMedicines)
;        }
        // dd($FMedicines);
        return $this->sendResponse($FMedicines, 'success');
    }

    public function changeStatus(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'medicine_id' => ['required' ,'exists:medicines,id'],
            'is_favorite' => ['required', 'boolean']
        ]);
        if ($validator->fails()) {
            return $this->sendError('missed data', $validator->errors());
        }

        $favorites = Favorite::withTrashed()->get();

        foreach ($favorites as $data) {
            if ($input['medicine_id'] == $data['medicine_id'] && Auth::user()->id == $data['user_id']) {
                if ($input['is_favorite'] == false) {
                    $data->delete();
                    return $this->sendResponse('done', 'success');
                }
                else{
                    $data->restore();
                    return $this->sendResponse($data, 'success');
                }
            }
        }

        $favorite = Favorite::create([
            'user_id' => Auth::user()->id,
            'medicine_id' => $input['medicine_id'],
            'is_favorite' => $input['is_favorite'],
        ]);

        return $this->sendResponse($favorite, 'success');
    }

    // public function update(Request $request, $id)
    // {
    //     $favorite = Favorite::find($id);
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'medicine_id' => 'required',
    //         'is_favorite' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError('missed data', $validator->errors());
    //     }
    //     // if($input['is_favorite'] == false)
    //     if ($favorite->is_favorite == false) {
    //         $favorite->delete();
    //     }
    //     return $this->sendResponse('done', 'success');
    // }
}
