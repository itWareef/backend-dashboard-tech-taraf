<?php

namespace App\Models;

use App\Core\Interfaces\FileUpload;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequestAttachment extends Model implements FileUpload
{
    protected $fillable=[
        'path',
        'maintenance_id'
    ];
    //
    public function documentFullPathStore(): string
    {
        return 'maintenance/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'path'
        ];
    }
}
