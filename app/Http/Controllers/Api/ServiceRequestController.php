<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ServiceRequestServices\ServiceRequestService;

class ServiceRequestController extends Controller
{

    public function index(){

    }

    public function store()
    {
        return (new ServiceRequestService())->storeNewRecord();
    }
}
