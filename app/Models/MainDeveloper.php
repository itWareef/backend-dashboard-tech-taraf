<?php

namespace App\Models;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class MainDeveloper extends Model implements FileUpload
{
    use HandleToArrayTrait;
    
    protected $fillable = [
        'name',
        'picture',
        'picture_logo',
        'space',
        'space_building',
    ];

    /**
     * Get the projects for the developer.
     */
    public function projects()
    {
        return $this->hasMany(MainProject::class, 'developer_id');
    }

    /**
     * Get the document full path for storing files.
     */
    public function documentFullPathStore(): string
    {
        return 'mainDevelopers/';
    }

    /**
     * Get the request keys for file uploads.
     */
    public function requestKeysForFile(): array
    {
        return [
            'picture',
            'picture_logo'
        ];
    }
}
