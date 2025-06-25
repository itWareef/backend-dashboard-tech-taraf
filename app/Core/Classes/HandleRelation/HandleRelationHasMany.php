<?php

namespace App\Core\Classes\HandleRelation;

use App\Core\Classes\HandleFiles\StoragePictures;
use App\Core\Interfaces\FileUpload;
use App\Core\Interfaces\HasManyRelations;
use App\Core\Traits\FilesHandleForCrud;
use Illuminate\Http\UploadedFile;

class HandleRelationHasMany
{
    use FilesHandleForCrud;

    public static function handleRelationHasMany($data, $model, $relationName): void
    {
        // Fetch existing related data
        $existingRecords = $model->$relationName()->get()->keyBy('id');

        if (!empty($data)) {
            foreach ($data as $value) {
                $recordId = $value['id'] ?? null;

                if ($recordId && $existingRecords->has($recordId)) {
                    $existingRecord = $existingRecords->get($recordId);
                    $existingRecord->update($value);
                } else {
                    $existingRecord = $model->$relationName()->create($value);
                }

                // Handle file uploads for the current record
                if (self::checkIfModelHasFile($existingRecord)) {
                    foreach ($existingRecord->requestKeysForFile() as $file) {
                        if (array_key_exists($file, $value)) {
                            if ($value[$file] instanceof UploadedFile) {
                                $newPath = StoragePictures::storeFile($value[$file], $existingRecord);
                                $existingRecord->{$file} = $newPath;
                                $existingRecord->save();
                            } elseif (isset($value[$file]) && $value[$file] === null) {
                                if ($existingRecord->{$file} !== null) {
                                    StoragePictures::deleteFile($existingRecord, $existingRecord->{$file});
                                    $existingRecord->{$file} = null;
                                    $existingRecord->save();
                                }
                            }
                        }
                    }
                }

                // Handle nested has-many relationships
                if (self::checkIfRelationHasMany($existingRecord)) {
                    $nestedRelations = $existingRecord->getHasManyRelations();
                    foreach ($nestedRelations as $nestedRelation => $nestedData) {
                        if (method_exists($existingRecord, $nestedRelation)) {
                            self::handleRelationHasMany($nestedData, $existingRecord, $nestedRelation);
                        }
                    }
                }

                // Remove the record from the list of existing records to preserve
                if ($recordId) {
                    $existingRecords->forget($recordId);
                }
            }
        }

        // Handle records to delete (remaining in $existingRecords)
        foreach ($existingRecords as $recordToDelete) {
            $recordToDelete->delete();
        }
    }

    private static function checkIfRelationHasMany($model): bool
    {
        return $model instanceof HasManyRelations;
    }

    private static function checkIfModelHasFile($model): bool
    {
        return $model instanceof FileUpload;
    }
}
