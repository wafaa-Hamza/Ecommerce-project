<?php

namespace App\Models;

use App\Enums\OrderStatusType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $fillable = ['status', 'user_id', 'product_id', 'total' , 'name' , 'email' , 'phone' , 'address' , 'address_2' , 'city' , 'postal_code' , 'shipping' , 'pay_on_delivery' , 'tax'];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class , 'order_product' , 'order_id' , 'product_id')->withPivot(['quantity' , 'price']);
    }


    protected function status(): Attribute
        {
            return Attribute::make(
                get: fn ($value) => isset($value) ? OrderStatusType::fromValue($value)->key : null,
                set: fn ($value) => isset($value) ? OrderStatusType::fromKey($value) : null,
            );
        }
    
}
