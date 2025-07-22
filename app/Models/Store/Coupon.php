<?php

namespace App\Models\Store;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_percentage',
        'start_date',
        'end_date',
        'type_permission',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        $today = Carbon::today();
        return $this->is_active &&
            $this->start_date <= $today &&
            $this->end_date >= $today &&
            (is_null($this->max_usage) || $this->times_used < $this->max_usage);
    }

    public function calculateDiscount($total)
    {
        return round(($total * $this->discount_percentage) / 100, 2);
    }
}
