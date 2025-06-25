<?php

namespace App\Models\Requests;

use App\Core\Interfaces\FileUpload;
use App\Models\Customer\Customer;
use App\Models\Project\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model implements FileUpload
{
    /** @use HasFactory<\Database\Factories\MaintenanceRequestFactory> */
    use HasFactory;
    protected $fillable=[
        'requester_id',
        'unit_id',
        'admin_id',
        'date',
        'picture',
        'notes',
        'status',
    ];
    public const STATUSES =[ 'in_progress', 'finished'];
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
        return $this->belongsTo(Unit::class,'unit_id');
    }
    public function admin(){
        return $this->belongsTo(User::class,'user_id');
    }
}
