<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'user_id' => $this->user_id,
            'medicine_id' => $this->medicine_id,
            'is_favorite' => $this->is_favorite
        ];
    }
}
