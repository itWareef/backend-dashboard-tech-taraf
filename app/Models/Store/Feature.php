<?php

namespace App\Models\Store;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model implements FileUpload
{
    use HandleToArrayTrait;

    protected $fillable = [
        'name',
        'picture',
        'description',
    ];

    public function documentFullPathStore(): string
    {
        return 'features/';
    }

    public function requestKeysForFile(): array
    {
        return ['picture'];
    }
}
