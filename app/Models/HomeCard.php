<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCard extends Model
{
    use HasFactory;

    public $fillable = ['home_group_id', 'image' , 'category_id'];

    public function cards(){
        return $this->belongsTo(HomeGroup::class);
    }
}
