<?php

namespace App\Core\Classes\StoringData;

use App\Core\Traits\DataArrayFromRequestTrait;
use App\Core\Traits\FilesHandleForCrud;
use App\Core\Traits\RelationsHandleForCrud;
use Illuminate\Support\Facades\DB;

abstract class AbstractClassHandleStoreData
{
    use RelationsHandleForCrud , DataArrayFromRequestTrait , FilesHandleForCrud;
    protected string $requestClass;
    protected  $modelClass ;

    protected array $data ;

    abstract public function model():string;
    abstract public function requestFile():string;
    public function __construct()
    {
        $this->setModelClass($this->model());
        $this->setRequestClass($this->requestFile());
    }
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function setModelClass(string $modelClass): void
    {
        $this->modelClass = $modelClass;
    }



    public function storeNewRecord(): array
    {
        return $this->createNewRecord();
    }

    private function createNewRecord()
    {
        $this->doBeforeValidation();
        $data = $this->getDataHandle();
        try {
            DB::beginTransaction();
            $this->handleActionInsideTransaction();
            $this->saveDataModel($data);
            $this->handleFileInData($data);
            $this->handleRelationInData($data);
            $this->doBeforeSuccessResponse();
            DB::commit();
            return [
                'status' => 'success',
                'data'  => $this->arrayData(),
                'messages' => $this->messageSuccessAction(),
                'code' => 200
            ];
        }catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }


    private function saveDataModel(array $data)
    {
        $model = app($this->getModelClass())::create($data);
        $this->modelClass = $model;
    }

    protected  function arrayData()
    {
        return [];
    }
    protected  function handleActionInsideTransaction()
    {
    }

}
