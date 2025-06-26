<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=[
        'name',
        'developer_name',
        'count',
        'place'
    ];
    public function units(){
        return $this->hasMany(Unit::class,'project_id');
    }
}
