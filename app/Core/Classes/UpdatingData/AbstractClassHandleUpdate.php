<?php

namespace App\Core\Classes\UpdatingData;

use App\Core\Traits\DataArrayFromRequestTrait;
use App\Core\Traits\FilesHandleForCrud;
use App\Core\Traits\RelationsHandleForCrud;
use Illuminate\Support\Facades\DB;

abstract class AbstractClassHandleUpdate
{
    use  DataArrayFromRequestTrait , FilesHandleForCrud ,RelationsHandleForCrud;
    protected string $requestClass;
    protected  $modelClass ;

    protected array $data ;
    abstract public function messageSuccessAction():string;

    abstract function requestFile():string;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
        $this->setRequestClass($this->requestFile());

    }

    public function update()
    {
        return $this->updateModel();
    }
    private function updateModel()
    {
        $this->doBeforeValidation();
        $data = $this->getDataHandle();
        try {
            DB::beginTransaction();
            $this->updateModelData( $data);
            $this->handleFileInData($data);
            $this->handleRelationInData($data);
            DB::commit();
            $this->doBeforeSuccessResponse();
            return [
                'status' => 'success',
                'messages' => $this->messageSuccessAction(),
                'code' => 200
            ];
        }catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }


    private function updateModelData(array $data)
    {
        $this->modelClass->update($data);
    }

}
