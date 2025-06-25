<?php

namespace App\Services\CustomerServices;

use App\Http\Requests\CustomerRequests\CustomerLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\CustomerOtpMail;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerOtp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class CustomerAuthService
{
    /**
     * Handle admin login process
     *
     * @param CustomerLoginRequest $request
     */
    public function login(CustomerLoginRequest $request)
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
     * @param CustomerLoginRequest $request
     * @return array
     */
    private function getCredentials(CustomerLoginRequest $request): array
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
     * @return Customer|null
     */
    private function getAdmin(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
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


    public function verifyOtp(VerifyOtpRequest $request)
    {
        $email = $request->email;
        $otp = $request->otp;

        $customer = Customer::where('email', $email)->first();
        if (!$customer) {
            return Response::error('المستخدم غير موجود', 404);
        }

        $record = CustomerOtp::where('customer_id', $customer->id)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return Response::error('رمز التحقق غير صالح أو منتهي', 401);
        }

        // حذف كل الرموز القديمة
//        CustomerOtp::where('customer_id', $customer->id)->delete();

        $token = $customer->createToken('authToken', ['customer'])->accessToken;
        return Response::success([
            'token' => $token,
            'user' => $customer
        ], ['تم تسجيل الدخول بنجاح'], 200);
    }

}
