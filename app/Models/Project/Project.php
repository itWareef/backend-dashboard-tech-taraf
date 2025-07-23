<?php

namespace App\Models\Project;

use App\Core\Interfaces\HasManyRelations;
use App\Models\Developer;
use App\Models\Guarantee;
use Illuminate\Database\Eloquent\Model;

class Project extends Model implements HasManyRelations
{
    protected $fillable=[
        'name',
        'developer_id',
        'count',
        'place'
    ];
    public function units(){
        return $this->hasMany(Unit::class,'project_id');
    }
    public function developer(){
        return $this->belongsTo(Developer::class,'developer_id');
    }
    public function guarantees()
    {
        return $this->belongsToMany(Guarantee::class, 'gurantee_project');
    }

    public function getHasManyRelations(): array
    {
        return [
            'guarantees'
        ];
    }
}
