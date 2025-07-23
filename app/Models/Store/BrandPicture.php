<?php

namespace App\Models\Store;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class BrandPicture extends Model implements FileUpload
{
    use HandleToArrayTrait;
    protected $table='brand_pictures';
    protected $fillable=[
        'path',
        'brand_id'
    ];
    //
    public function documentFullPathStore(): string
    {
        return 'brandPictures/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'path'
        ];
    }
}
