<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class GardenRequestAttachment extends Model implements FileUpload
{
    use HandleToArrayTrait;
    protected $table='garden_request_attachments';
    protected $fillable=[
        'path',
        'garden_id'
    ];
    //
    public function documentFullPathStore(): string
    {
        return 'gardenRequests/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'path'
        ];
    }
}
