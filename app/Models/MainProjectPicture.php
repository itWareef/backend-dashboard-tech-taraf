<?php

namespace App\Models;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class MainProjectPicture extends Model implements FileUpload
{
    use HandleToArrayTrait;
    
    protected $table = 'main_project_pictures';
    
    protected $fillable = [
        'path',
        'project_id'
    ];

    /**
     * Get the project that owns the picture.
     */
    public function project()
    {
        return $this->belongsTo(MainProject::class, 'project_id');
    }

    /**
     * Get the document full path for storing files.
     */
    public function documentFullPathStore(): string
    {
        return 'mainProjects/';
    }

    /**
     * Get the request keys for file uploads.
     */
    public function requestKeysForFile(): array
    {
        return [
            'path'
        ];
    }
}
