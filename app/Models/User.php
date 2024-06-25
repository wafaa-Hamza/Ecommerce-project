<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\GenderType;
use App\Enums\OAuthType;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles , InteractsWithMedia ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        //'phone',
        // 'otp',
        // 'email_verified_at',
        // 'gender',
        // 'nick',
        // 'address',
        // 'active_status',
        // 'last_seen'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function gender(): Attribute
        {
            return Attribute::make(
                get: fn ($value) => isset($value) ? GenderType::fromValue($value)->key : null,
                set: fn ($value) => isset($value) ? GenderType::fromKey($value) : null,
            );
        }

    public function products()
    {
        return $this->hasMany(Product::class , 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class , 'user_id');
    }

    public function cart()
    {
        return $this->belongsToMany(Product::class , 'product_user')->withPivot('quantity');
    }

    public function wish_list()
    {
        return $this->belongsToMany(Product::class , 'wish_list');
    }

    public function chat_rooms(){
        return $this->hasMany(ChatRoom::class , 'user1_id')->orWhere('user2_id' , $this->id);
    }


}
