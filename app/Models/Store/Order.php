<?php

namespace App\Models\Store;

use App\Models\Customer\Customer;
use App\Models\Project\Unit;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->number)) {
                $order->number = NumberingService::generateNumber(Order::class);
            }
        });
    }

    // 🔗 العلاقة مع الزبون
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 العلاقة مع الكوبون
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // 🔗 العلاقة مع العناصر داخل الطلب (order_items)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🧮 إجمالي السعر الأصلي بدون خصم
    public function getOriginalTotalAttribute()
    {
        return $this->total_price + $this->discount_amount;
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
