<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'brand_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
