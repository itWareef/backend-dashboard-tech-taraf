<?php

namespace App\Models\Project;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
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
}
