<?php

namespace App\Models;

use App\Core\Interfaces\FileUpload;
use Illuminate\Database\Eloquent\Model;

class PlantingRequestAttachment extends Model implements FileUpload
{
    use HandleToArrayTrait;

    protected $fillable=[
    'path',
    'planting_id'
];
    //
    public function documentFullPathStore(): string
    {
        return 'planting/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'path'
        ];
    }
}
