<?php

namespace App\Models\Store;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class Section extends Model implements FileUpload
{
    use HandleToArrayTrait;
    protected $fillable = ['name', 'picture'];

    public function documentFullPathStore(): string
    {
       return 'sections/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'picture'
        ];
    }
}
