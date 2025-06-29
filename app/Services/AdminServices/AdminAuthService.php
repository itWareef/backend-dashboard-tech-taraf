<?php

namespace App\Services\AdminServices;

use App\Http\Requests\AdminRequests\AdminLoginRequest;
use App\Http\Requests\CustomerRequests\CustomerLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\CustomerOtpMail;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerOtp;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class AdminAuthService
{
    /**
     * Handle admin login process
     *
     * @param AdminLoginRequest $request
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $this->getCredentials($request);
        $customer = $this->getAdmin($credentials['email']);

        if (!$customer || !$this->verifyPassword($credentials['password'], $customer->password)) {
            return Response::error('البريد الإلكتروني أو كلمة المرور غير صحيحة', 401);
        }


        $token = $customer->createToken('authToken', ['api'])->accessToken;
        return Response::success([
            'token' => $token,
            'user' => $customer
        ], ['تم تسجيل الدخول بنجاح'], 200);
    }


    /**
     * Get credentials from request
     *
     * @param AdminLoginRequest $request
     * @return array
     */
    private function getCredentials(AdminLoginRequest $request): array
    {
        return [
            'email' => $request->email,
            'password' => $request->password
        ];
    }

    /**
     * Get admin by email
     *
     * @param string $email
     * @return User|null
     */
    private function getAdmin(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Verify password
     *
     * @param string $inputPassword
     * @param string $hashedPassword
     * @return bool
     */
    private function verifyPassword(string $inputPassword, string $hashedPassword): bool
    {
        return password_verify($inputPassword, $hashedPassword);
    }



}
