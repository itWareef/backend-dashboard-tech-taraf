<?php

namespace App\Models;

use App\Core\Interfaces\FileUpload;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model implements FileUpload
{
    use HandleToArrayTrait;
    protected $fillable = [
        'picture',
        'name',
        'email',
        'bank_account',
        'tax_number',
    ];

    public function documentFullPathStore(): string
    {
        return 'developers/';
    }

    public function requestKeysForFile(): array
    {
        return ['picture'];
    }
}
