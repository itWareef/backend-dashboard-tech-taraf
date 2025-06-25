<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model
{
    protected $fillable = [
        'customer_id',
        'otp',
        'expires_at',
        'used'
    ];

    protected $dates = ['expires_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
