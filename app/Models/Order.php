<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
    'pharmacies_id'   ,
    'medicines_id'   ,
    'status'   ,
    'paid status'  ,
    'favorite' ,
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

    public function users()
    {
        return $this->belongsTo(User::class , 'pharmacies_id');
    }}
