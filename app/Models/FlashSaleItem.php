<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FlashSaleItem extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    protected $fillable = ['product_id' , 'discount' , 'flash_sale_id'];
    
    protected function product(){
        return $this->belongsTo(Product::class);
    }
}
