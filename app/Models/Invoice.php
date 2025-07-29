<?php

namespace App\Models;

use App\Models\Customer\Customer;
use App\Models\HandleToArrayTrait;
use App\Models\Requests\MaintenanceRequest;
use App\Models\Requests\PlantingRequest;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends Model
{
    use HasFactory, HandleToArrayTrait;

    protected $fillable = [
        'customer_id',
        'amount',
        'status',
        'description',
        'number',
        'invoiceable_type',
        'invoiceable_id'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (empty($invoice->number)) {
                $invoice->number = NumberingService::generateNumber(Invoice::class);
            }
        });
    }

    public const STATUSES = ['paid', 'unpaid'];
    public const PAID = 'paid';
    public const UNPAID = 'unpaid';

    /**
     * Get the customer that owns the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the parent invoiceable model (MaintenanceRequest, PlantingRequest, etc.).
     */
    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::PAID);
    }

    /**
     * Scope a query to only include unpaid invoices.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', self::UNPAID);
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === self::PAID;
    }

    /**
     * Check if invoice is unpaid.
     */
    public function isUnpaid(): bool
    {
        return $this->status === self::UNPAID;
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(): void
    {
        $this->update(['status' => self::PAID]);
    }

    /**
     * Mark invoice as unpaid.
     */
    public function markAsUnpaid(): void
    {
        $this->update(['status' => self::UNPAID]);
    }
}
