<?php

namespace App\Core\Classes\DeletingData;

use App\Core\Classes\HandleFiles\StoragePictures;
use App\Core\Interfaces\FileUpload;

abstract class AbstractClassHandleDelete
{
    protected  $modelClass ;
    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }
    abstract public function messageSuccessAction():string;

    public function delete(){
        return $this->handleDeleteModelAndRelatedFiles();
    }

    private function handleDeleteModelAndRelatedFiles():array
    {
        $model = $this->modelClass;
        if ($model instanceof FileUpload){
            foreach ($model->requestKeysForFile() as $file){
                if ($model->{$file}){
                    StoragePictures::deleteFile($model , $model->{$file});
                }
            }
        }

        $delete = $model->delete();
        if ($delete){
            return [
                'status' => 'success',
                'messages' => $this->messageSuccessAction(),
                'code' => 200
            ];
        }else{
            return [
                'status' => 'error',
                'messages' => $this->messageFailedAction(),
                'code' => 404
            ];
        }
    }

    private function messageFailedAction():string
    {
        return 'Delete Not Completed Maybe Use in Our System Or Error';
    }
}
