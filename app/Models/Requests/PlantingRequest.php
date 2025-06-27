<?php

namespace App\Models\Requests;

use App\Core\Interfaces\FileUpload;
use App\Models\Customer\Customer;
use App\Models\Project\Project;
use App\Models\Project\Unit;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantingRequest extends Model implements FileUpload
{
    /** @use HasFactory<\Database\Factories\MaintenanceRequestFactory> */
    use HasFactory;
    protected $fillable=[
        'requester_id',
        'project_id',
        'unit_id',
        'date',
        'picture',
        'notes',
        'status',
        'time',
        'otp',
        'rating',
        'visits_count'
    ];
    public const STATUSES =[ 'in_progress', 'finished','waiting_rating'];
    public const FINISHED = 'finished';
    public const WAITING_RATING = 'waiting_rating';
    public const IN_PROGRESS = 'in_progress';
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
}
