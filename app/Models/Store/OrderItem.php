<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'brand_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
