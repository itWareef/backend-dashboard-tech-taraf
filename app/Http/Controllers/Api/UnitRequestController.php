<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\UnitRequestServices\UnitRequestService;

class UnitRequestController extends Controller
{

    public function index(){

    }

    public function store()
    {
        return (new UnitRequestService())->storeNewRecord();
    }
}
