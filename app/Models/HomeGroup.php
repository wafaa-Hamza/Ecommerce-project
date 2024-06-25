<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeGroup extends Model
{
    use HasFactory;

    public $fillable = ['title' , 'user_id'];

    public function cards(){
        return $this->hasMany(HomeCard::class);
    }
}
