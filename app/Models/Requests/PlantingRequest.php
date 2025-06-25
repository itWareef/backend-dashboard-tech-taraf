<?php

namespace App\Models\Requests;

use App\Core\Interfaces\FileUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantingRequest extends Model implements FileUpload
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
}
