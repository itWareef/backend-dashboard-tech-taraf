<?php

namespace App\Models\Requests;

use App\Core\Interfaces\FileUpload;
use App\Models\Customer\Customer;
use App\Models\HandleToArrayTrait;
use App\Models\MaintenanceRequestAttachment;
use App\Models\PlantingRequestAttachment;
use App\Models\Project\Project;
use App\Models\Project\Unit;
use App\Models\Supervisor;
use App\Models\Invoice;
use App\Models\SuperVisorVisit;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PlantingRequest extends Model implements FileUpload
{
    /** @use HasFactory<\Database\Factories\MaintenanceRequestFactory> */
    use HasFactory ,HandleToArrayTrait;
    protected $fillable=[
        'requester_id',
        'project_id',
        'category_id',
        'unit_id',
        'date',
        'picture',
        'phone',
        'notes',
        'status',
        'time',
        'otp',
        'rating',
        'visits_count',
        'number'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plantingRequest) {
            if (empty($plantingRequest->number)) {
                $plantingRequest->number = NumberingService::generateNumber(PlantingRequest::class);
            }
        });
    }

    public const STATUSES =[ 'in_progress', 'finished','waiting_rating','cancelled'];
    public const FINISHED = 'finished';
    public const WAITING_RATING = 'waiting_rating';
    public const IN_PROGRESS = 'in_progress';
    public const CANCELLED = 'cancelled';
    public function documentFullPathStore(): string
    {
        return 'MaintenanceRequests/'.$this->id.'/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'picture'
        ];
    }
    public function requester()
    {
        return $this->belongsTo(Customer::class, 'requester_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'project_id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'unit_id');
    }
    public function supervisors()
    {
       return $this->hasOne(PlantingSuperVisor::class ,'planting_id');
    }
    public function superVisorVisits(): MorphMany
    {
        return $this->morphMany(SuperVisorVisit::class, 'request');
    }
    public function attachments()
    {
        return $this->hasMany(PlantingRequestAttachment::class ,'planting_id');
    }

    /**
     * Get the invoice for this planting request.
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
