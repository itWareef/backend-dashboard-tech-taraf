<?php

namespace App\Core\Traits;

use App\Core\Classes\HandleFiles\StoragePictures;
use App\Core\Interfaces\FileUpload;
use Illuminate\Http\UploadedFile;

trait FilesHandleForCrud
{
    protected function handleFileInData(array $data):void
    {
        if ($this->checkIfModelHasFile()){
            foreach ($this->modelClass->requestKeysForFile() as  $file){
                if (array_key_exists($file , $data)) {
                    if ($data[$file] instanceof UploadedFile){
                        $newPath = StoragePictures::storeFile($data[$file], $this->modelClass);
                        $this->modelClass->{$file} = $newPath;
                        $this->modelClass->save();
                    }
                    if (isset($data[$file]) && $data[$file]== null){
                        if ( $this->modelClass->{$file} != null){
                            StoragePictures::deleteFile($this->modelClass , $this->modelClass->{$file});
                        }
                    }

                }
            }
        }
    }
    protected function checkIfModelHasFile():bool
    {
        return $this->modelClass instanceof FileUpload;
    }


}
