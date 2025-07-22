<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ContractRequestService\ContractRequestService;
use App\Services\GardenRequestServices\GardenRequestService;

class GardenRequestController extends Controller
{

    public function index(){

    }

    public function store()
    {
        return (new GardenRequestService())->storeNewRecord();
    }
}
