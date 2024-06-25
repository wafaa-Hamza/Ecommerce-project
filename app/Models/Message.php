<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'sender_id',
        'receiver_id'
    ];

    public function chat_room(){
        return $this->belongsTo(ChatRoom::class);
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reciver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}

