<?php

namespace App\Models\Store;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Model;

class BrandFavourite extends Model
{
    protected $fillable = ['customer_id', 'brand_id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
