<?php

namespace App\Models\Store;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['customer_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
