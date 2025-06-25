<?php

namespace App\Core\Traits;

use App\Core\Interfaces\FileUpload;
use App\Core\Interfaces\HasManyRelations;
use App\Core\Interfaces\HasOneRelations;

trait RelationChecks
{
    protected static function checkIfRelationHasMany($model): bool
    {
        return $model instanceof HasManyRelations;
    }

    protected static function checkIfRelationModelHasFile($model): bool
    {
        return $model instanceof FileUpload;
    }

    protected static function checkIfRelationHasOne(mixed $model):bool
    {
        return $model instanceof HasOneRelations;

    }
}
