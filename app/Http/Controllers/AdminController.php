<?php

namespace App\Http\Controllers;


use App\Http\Requests\AdminRequests\AdminLoginRequest;

use App\Models\User;
use App\Services\AdminServices\AdminAuthService;

use App\Services\AdminServices\AdminUpdatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class AdminController extends Controller
{

    public function login(AdminLoginRequest $request)
    {
        return (new AdminAuthService())->login($request);
    }
    public function me(Request $request)
    {
        $customer = $request->user('api');
        $data = $customer->toArray();
        return Response::success($data);
    }
    public function update(User $user)
    {
        return (new AdminUpdatingService($user))->update() ;
    }
    public function logout(Request $request)
    {
        $request->user('api')->token()->revoke();
        return Response::success([], ['Successfully logged out'] , 200);
    }

}
