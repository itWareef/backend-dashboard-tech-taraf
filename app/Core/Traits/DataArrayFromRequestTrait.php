<?php

namespace App\Core\Traits;

use App\Exceptions\CustomValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait DataArrayFromRequestTrait
{
    abstract public function messageSuccessAction():string;

    public function setRequestClass(string $requestClass): void
    {
        $this->requestClass = $requestClass;
    }

    public function getRequestClass(): string
    {
        return $this->requestClass;
    }
    protected function doBeforeValidation():void
    {
    }
    protected function doBeforeSuccessResponse():void
    {
    }
    public function setData():void
    {
        $this->data = $this->validatedData();
    }

    protected function getDataHandle():array
    {
        $this->setData();
        return $this->data;
    }
    protected function validatedData():array{
        $request = request();
        $validator = Validator::make($request->all(), resolve($this->getRequestClass())->rules());
        if ($validator->fails()) {
            throw new CustomValidationException($validator);
        }

        return $request->all();
    }
}
