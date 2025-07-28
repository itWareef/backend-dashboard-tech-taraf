<?php

namespace App\Models\Store;

use App\Core\Interfaces\FileUpload;
use App\Core\Interfaces\HasManyRelations;
use App\Core\Interfaces\ManyToManyRelations;
use App\Models\HandleToArrayTrait;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model implements FileUpload ,ManyToManyRelations , HasManyRelations
{
    use HandleToArrayTrait;

    protected $fillable = [
        'name',
        'picture',
        'description',
        'price',
        'section_id',
        'number',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($brand) {
            if (empty($brand->number)) {
                $brand->number = NumberingService::generateNumber(Brand::class);
            }
        });
    }

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

    public function pictures()
    {
        return $this->hasMany(BrandPicture::class,'brand_id');
    }
    public function getHasManyRelations(): array
    {
        return [
            'pictures'
        ];
    }
}
