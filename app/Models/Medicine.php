<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ahmetsabri\FatihLaravelSearch\Searchable;


class Medicine extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    protected $fillable = [
        'scientific_name','trade_name',
       'company_name', 'categories_name','quantity',
       'expiration_at','price','form','details'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class);
    } //edited

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function users(): BelongsToMany
 {
     return $this->belongsToMany(User::class,);
 }

 protected $casts = [
    'expiration_at' => 'datetime:d/m/Y', // Change your format
];

 protected $searchable = [
    'scientific_name' ,
    'trade_name' ,
    'categories_name'
 ];
}
