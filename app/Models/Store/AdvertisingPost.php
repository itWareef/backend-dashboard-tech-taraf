<?php

namespace App\Models\Store;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPost extends Model implements FileUpload
{
    use  HandleToArrayTrait;
    protected $fillable = [
        'title',
        'picture',
    ];

    public function documentFullPathStore(): string
    {
      return 'advertisingPost/';
    }

    public function requestKeysForFile(): array
    {
        return ['picture'];
    }
}
