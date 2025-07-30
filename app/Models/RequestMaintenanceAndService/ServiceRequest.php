<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Core\Interfaces\FileUpload;
use App\Models\Category;
use App\Models\Customer\Customer;
use App\Models\HandleToArrayTrait;
use App\Models\Invoice;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ServiceRequest extends Model implements FileUpload
{
    use HandleToArrayTrait;
    protected $fillable = [
        'requester_id',
        'location',
        'unit',
        'category_id',
        'date',
        'picture',
        'time',
        'phone',
        'otp',
        'notes',
        'rating',
        'visits_count',
        'type',
        'status',
        'number',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($serviceRequest) {
            if (empty($serviceRequest->number)) {
                $serviceRequest->number = NumberingService::generateNumber(ServiceRequest::class);
            }
        });
    }

    // Status constants
    public const STATUSES = ['pending', 'in_progress', 'completed', 'cancelled'];
    public const PENDING = 'pending';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    protected $casts = [
        'date' => 'date',
        'rating' => 'double',
        'visits_count' => 'integer',
    ];

    public function requester()
    {
        return $this->belongsTo(Customer::class,'requester_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function documentFullPathStore(): string
    {
       return 'ServiceRequest/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'picture'
        ];
    }

    /**
     * Get the invoice for this service request.
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
