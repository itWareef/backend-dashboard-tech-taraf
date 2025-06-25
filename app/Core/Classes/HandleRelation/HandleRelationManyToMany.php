<?php

namespace App\Core\Classes\HandleRelation;

class HandleRelationManyToMany
{
    public static function HandleRelationManyToMany($data , $model , $relationName):void
    {
        if ($data == null || $data[0] == null) {
            $data = [] ;
        }
        $model->$relationName()->sync($data);
    }
}
