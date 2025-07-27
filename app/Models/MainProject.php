<?php

namespace App\Models;

use App\Core\Interfaces\HasManyRelations;
use Illuminate\Database\Eloquent\Model;

class MainProject extends Model implements HasManyRelations
{
    protected $fillable = [
        'name',
        'description',
        'developer_id',
        'low_price',
        'high_price',
        'unit_count',
        'price_precentage',
        'youtube_link',
        'low_space',
        'high_space',
    ];

    /**
     * Get the developer that owns the project.
     */
    public function developer()
    {
        return $this->belongsTo(MainDeveloper::class, 'developer_id');
    }

    /**
     * Get the pictures for the project.
     */
    public function pictures()
    {
        return $this->hasMany(MainProjectPicture::class, 'project_id');
    }

    /**
     * Get the has many relations for this model.
     */
    public function getHasManyRelations(): array
    {
        return [
            'pictures'
        ];
    }
}
