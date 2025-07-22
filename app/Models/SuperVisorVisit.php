<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SuperVisorVisit extends Model
{
    protected $fillable = [
        'supervisor_id',
        'request_type',
        'request_id',
        'notes',
        'reason'
    ];

    public function request(): MorphTo
    {
        return $this->morphTo();
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
