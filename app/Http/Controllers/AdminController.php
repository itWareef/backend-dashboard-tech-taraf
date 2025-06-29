<?php

namespace App\Http\Controllers;


use App\Http\Requests\AdminRequests\AdminLoginRequest;

use App\Services\AdminServices\AdminAuthService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class AdminController extends Controller
{

    public function login(AdminLoginRequest $request)
    {
        return (new AdminAuthService())->login($request);
    }


    public function logout(Request $request)
    {
        $request->user('api')->token()->revoke();
        return Response::success([], ['Successfully logged out'] , 200);
    }

}
