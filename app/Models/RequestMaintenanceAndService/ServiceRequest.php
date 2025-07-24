<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Core\Interfaces\FileUpload;
use App\Models\Category;
use App\Models\Customer\Customer;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

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
    ];

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
}
