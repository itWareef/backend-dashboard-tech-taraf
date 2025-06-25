<?php

namespace App\Core\Classes\HandleRelation;

use App\Core\Classes\HandleFiles\StoragePictures;
use App\Core\Interfaces\FileUpload;
use App\Core\Interfaces\HasManyRelations;
use App\Core\Interfaces\HasOneRelations;
use App\Core\Traits\FilesHandleForCrud;
use App\Core\Traits\RelationChecks;
use Illuminate\Http\UploadedFile;

class HandleRelationHasOne
{
    use FilesHandleForCrud ,RelationChecks;

    public static function handleRelationHasOne($data, $model, $relationName): void
    {
        $existingRecord = $model->$relationName;

        if ($existingRecord) {
            $existingRecord->update($data);
        } else {
            $existingRecord = $model->$relationName()->create($data);
        }

        if (!empty($data)) {
            if (self::checkIfRelationModelHasFile($existingRecord)) {
                foreach ($existingRecord->requestKeysForFile() as $file) {
                    if (array_key_exists($file, $data)) {
                        if ($data[$file] instanceof UploadedFile) {
                            $newPath = StoragePictures::storeFile($data[$file], $existingRecord);
                            $existingRecord->{$file} = $newPath;
                            $existingRecord->save();
                        } elseif (isset($data[$file]) && $data[$file] === null) {
                            if ($existingRecord->{$file} !== null) {
                                StoragePictures::deleteFile($existingRecord, $existingRecord->{$file});
                                $existingRecord->{$file} = null;
                                $existingRecord->save();
                            }
                        }
                    }
                }
            }

            if (self::checkIfRelationHasMany($existingRecord)) {
                $nestedRelations = $existingRecord->getHasManyRelations();
                foreach ($nestedRelations as $nestedRelation => $nestedData) {
                    if (method_exists($existingRecord, $nestedRelation)) {
                        HandleRelationHasMany::handleRelationHasMany($nestedData, $existingRecord, $nestedRelation);
                    }
                }
            }
            if (self::checkIfRelationHasOne($existingRecord)) {
                $nestedRelations = $existingRecord->getHasManyRelations();
                foreach ($nestedRelations as $nestedRelation => $nestedData) {
                    if (method_exists($existingRecord, $nestedRelation)) {
                        self::handleRelationHasOne($nestedData, $existingRecord, $nestedRelation);
                    }
                }
            }
        }
    }


}
