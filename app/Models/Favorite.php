<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

   protected $fillable = [
        'user_id',
        'medicine_id',
        'is_favorite',
   ];

   public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function mesicines()
    {
        return $this->belongsTo(Medicine::class);
    }

}
