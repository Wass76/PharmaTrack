<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    } //edited

}
