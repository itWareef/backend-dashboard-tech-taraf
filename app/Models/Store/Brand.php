<?php

namespace App\Models\Store;

use App\Core\Interfaces\FileUpload;
use App\Core\Interfaces\ManyToManyRelations;
use App\Models\HandleToArrayTrait;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model implements FileUpload ,ManyToManyRelations
{
    use HandleToArrayTrait;

    protected $fillable = [
        'name',
        'picture',
        'description',
        'price',
        'section_id',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function documentFullPathStore(): string
    {
        return 'brands/';
    }

    public function requestKeysForFile(): array
    {
        return ['picture'];
    }
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_brand');
    }

    public function getManyToManyRelations(): array
    {
        return [
            'features'
        ];
    }
}
