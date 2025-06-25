<?php

namespace App\Core\Traits;

use App\Core\Classes\HandleRelation\HandleRelationHasMany;
use App\Core\Classes\HandleRelation\HandleRelationHasOne;
use App\Core\Classes\HandleRelation\HandleRelationManyToMany;
use App\Core\Interfaces\HasManyRelations;
use App\Core\Interfaces\HasOneRelations;
use App\Core\Interfaces\ManyToManyRelations;

trait RelationsHandleForCrud
{
    protected function handleRelationInData(array $data):void
    {
        if ($this->checkRelationHasMany()){
            foreach ($this->modelClass->getHasManyRelations() as $relation){
                if (array_key_exists($relation , $data)){
                    HandleRelationHasMany::handleRelationHasMany($data[$relation] , $this->modelClass , $relation);
                }
            }
        }
        if ($this->checkRelationManyToMany()){

            foreach ($this->modelClass->getManyToManyRelations() as $relation) {
                if (array_key_exists($relation , $data)) {
                    HandleRelationManyToMany::HandleRelationManyToMany($data[$relation], $this->modelClass, $relation);
                }
            }
        }
        if ($this->checkRelationHasOne()){

            foreach ($this->modelClass->getHasOneRelations() as $relation) {
                if (array_key_exists($relation , $data)) {
                    HandleRelationHasOne::handleRelationHasOne($data[$relation], $this->modelClass, $relation);
                }
            }
        }
    }


    private function checkRelationHasMany():bool
    {
        return $this->modelClass instanceof HasManyRelations;
    }
    private function checkRelationHasOne():bool
    {
        return $this->modelClass instanceof HasOneRelations;
    }
    private function checkRelationManyToMany():bool
    {
        return $this->modelClass instanceof ManyToManyRelations;
    }



}
