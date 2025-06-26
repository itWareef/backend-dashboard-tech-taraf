<?php

namespace App\Services\SuperVisorServices;

use App\Http\Requests\CustomerRequests\CustomerLoginRequest;
use App\Http\Requests\SupervisorRequests\SupervisorLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\CustomerOtpMail;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerOtp;
use App\Models\Supervisor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class SupervisorAuthService
{
    /**
     * Handle admin login process
     *
     * @param SupervisorLoginRequest $request
     */
    public function login(SupervisorLoginRequest $request)
    {
        $credentials = $this->getCredentials($request);
        $customer = $this->getAdmin($credentials['email']);

        if (!$customer || !$this->verifyPassword($credentials['password'], $customer->password)) {
            return Response::error('البريد الإلكتروني أو كلمة المرور غير صحيحة', 401);
        }


        $token = $customer->createToken('authToken', ['supervisor'])->accessToken;
        return Response::success([
            'token' => $token,
            'user' => $customer
        ], ['تم تسجيل الدخول بنجاح'], 200);
    }

    private function storeOtpInDb(Customer $customer, string $otp): void
    {
        CustomerOtp::create([
            'customer_id' => $customer->id,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    }
    private function sendOtpEmail(string $email, string $otp): void
    {
        Mail::to($email)->send(new CustomerOtpMail($otp));
    }
    /**
     * Get credentials from request
     *
     * @param SupervisorLoginRequest $request
     * @return array
     */
    private function getCredentials(SupervisorLoginRequest $request): array
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
     * @return Supervisor|null
     */
    private function getAdmin(string $email): ?Supervisor
    {
        return Supervisor::where('email', $email)->first();
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
