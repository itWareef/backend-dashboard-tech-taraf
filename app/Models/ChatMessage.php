<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable=[
        'user_id',
        'message' ,
        'sender',
    ];
    public function sender()
    {
        return $this->morphTo();
    }

    public function thread()
    {
        return $this->belongsTo(ChatThread::class);
    }

}
