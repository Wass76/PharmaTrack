<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' =>$this->id,
            'name' => $this->name,
            'userName' => $this->userName,
            'userNumber' => $this->userNumber,
            'address' => $this->address,
            'email' => $this->email,
        ];
    }
}
