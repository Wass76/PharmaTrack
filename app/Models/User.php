<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'userName',
        'userNumber',
        'address',
        'password',
        'confirm_password' ,
    ];


    public function orders(): HasMany
 {
     return $this->hasMany(Order::class);
 } //edited


 public function medicines(): BelongsToMany
 {
     return $this->belongsToMany(Medicine::class,);
 }


    protected $hidden = [
        'password',
        'remember_token',
        'confirm_password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'confirm_password' => 'hashed',
    ];


    protected function isManager(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->role_id==2,
        );
    }

    protected function isSecretary(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->role_id==3,
        );
    }

    protected function isSalesOfficer(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->role_id==4,
        );
    }
}
