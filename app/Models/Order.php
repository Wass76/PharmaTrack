<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
    'cart_id'   ,
    'quantity'   ,
    'medicines_name',
    'price'
    ];
    /**
     * The roles that belong to the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class);
    }



    public function carts()
    {
        return $this->belongsTo(Cart::class);
    }
}
