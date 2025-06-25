<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\SendOtpMail;
use App\Models\Customer\EmailOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class OtpController extends Controller
{
    public function send(SendOtpRequest $request)
    {
        $email = $request->email;
        $otp = rand(1000, 9999);

        EmailOtp::where('email', $email)->delete(); // Clear old OTPs

        EmailOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        Mail::to($email)->send(new SendOtpMail($otp));

        return Response::success(['otp' => $otp],['تم إرسال رمز التحقق إلى بريدك الإلكتروني.']);
    }

    public function verify(VerifyOtpRequest $request)
    {
        $otpRecord = EmailOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord) {
            return Response::error( 'OTP غير صحيح.');
        }

        if ($otpRecord->isExpired()) {
            return Response::error( 'انتهت صلاحية رمز التحقق.');
        }

        $otpRecord->delete(); // Invalidate OTP

        return Response::success([],['تم التحقق من الرمز بنجاح.']);
    }
}
