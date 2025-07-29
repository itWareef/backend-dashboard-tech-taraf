<?php

namespace App\Models\Store;

use App\Events\OrderCreated;
use App\Models\Customer\Customer;
use App\Models\Invoice;
use App\Models\Project\Unit;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

        static::created(function ($order) {
            // Dispatch event to create invoice automatically
            event(new OrderCreated($order));
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

    /**
     * Get the invoice for this order.
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
