<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable=[
        'requester_id',
        'note'
    ];
}
