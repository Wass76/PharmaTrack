<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id'   ,
        'status'  , // New - Preparing - Delivering - Received
        'Total_price',
        'paid_status'
        ];

    public function orders()

    {
        return $this->hasMany(Order::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    } //e
}
