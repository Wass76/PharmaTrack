<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class MedicineFavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scientific_name' => $this->scientific_name,
            'trade_name' =>$this->trade_name,
            'company_name' => $this->company_name,
             'categories_name' => $this->categories_name,
             'quantity'=>$this->quantity,
            'price' =>$this->price,
            'photo' =>$this->photo,
            'form' =>$this->form,
            'details' => $this->details,
            'expiration_at' => $this->expiration_at ,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this -> created_at,
            'updated_at' => $this-> updated_at,
            'favorite' => count(Favorite::where('user_id', Auth::user()->id)->where('medicine_id', $this->id)->get()) == 0 ? 0 : 1 ,
        ];
    }
}
