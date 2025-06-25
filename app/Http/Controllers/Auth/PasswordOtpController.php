<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Models\PasswordOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class PasswordOtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        $otp = rand(1000, 9999);

        PasswordOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        Mail::to($request->email)->send(new \App\Mail\SendPasswordOtp($otp));

        return Response::success(['otp' => $otp],['تم إرسال رمز التحقق إلى بريدك الإلكتروني.']);
    }
    public function verifyOtpAndReset(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $record = PasswordOtp::where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return Response::error('OTP غير صالح أو منتهي الصلاحية.');
        }

        // إعادة تعيين كلمة المرور
        DB::transaction(function () use ($record , $request) {
            $user = Customer::where('email', $record->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // حذف الـ OTP
            $record->delete();
        });

        return Response::success([],['تم إعادة تعيين كلمة المرور بنجاح.']);
    }

}
