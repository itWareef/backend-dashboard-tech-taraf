<?php

namespace App\Services\SuperVisorServices;

use App\Http\Requests\SupervisorRequests\SupervisorLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\CustomerOtpMail;
use App\Models\Customer\EmailOtp;
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

        $otp = rand(1000, 9999);
        $this->storeOtpInDb($customer, $otp);
        $this->sendOtpEmail($customer->email, $otp);
        return Response::success(['otp' => $otp], [ 'تم إرسال رمز التحقق إلى بريدك الإلكتروني' ,], 200);

    }

    private function storeOtpInDb(Supervisor $customer, string $otp): void
    {
        EmailOtp::create([
            'email' => $customer->email,
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
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $email = $request->email;
        $otp = $request->otp;

        $supervisor = Supervisor::where('email', $email)->first();
        if (!$supervisor) {
            return Response::error('المستخدم غير موجود', 404);
        }

        $record = EmailOtp::where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return Response::error('رمز التحقق غير صالح أو منتهي', 401);
        }

        // حذف كل الرموز القديمة
//        CustomerOtp::where('customer_id', $customer->id)->delete();

        $token = $supervisor->createToken('authToken', ['supervisor'])->accessToken;
        return Response::success([
            'token' => $token,
            'user' => $supervisor
        ], ['تم تسجيل الدخول بنجاح'], 200);
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
