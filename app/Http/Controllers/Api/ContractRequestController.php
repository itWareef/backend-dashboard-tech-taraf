<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ContractRequestService\ContractRequestService;

class ContractRequestController extends Controller
{

    public function index(){

    }

    public function store()
    {
        return (new ContractRequestService())->storeNewRecord();
    }
}
