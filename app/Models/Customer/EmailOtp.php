<?php

namespace App\Models\Customer;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at'];

    public function isExpired()
    {
        return Carbon::now()->gt($this->expires_at);
    }
}
