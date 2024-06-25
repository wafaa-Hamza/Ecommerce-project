<?php

namespace App\Models;

use App\Enums\AttributeType;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'value' , 'product_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    protected function type(): CastsAttribute
    {
        return CastsAttribute::make(
            get: fn ($value) => AttributeType::fromValue($value)->key,
            set: fn ($value) => AttributeType::fromKey($value),
        );
    }
}
