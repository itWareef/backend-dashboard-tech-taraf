<?php

namespace App\Services\CustomerServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\CustomerRequests\RegisterCustomerRequest;
use App\Mail\SendOtpMail;
use App\Models\Customer\Customer;
use App\Models\Customer\EmailOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterCustomer extends AbstractClassHandleStoreData
{
    protected $otp='';

    public function model(): string
    {
        return Customer::class;
    }

    public function requestFile(): string
    {
       return RegisterCustomerRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل مستخدم جديد";
    }

    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['password'] = Hash::make($data['password']);
        $data['name'] = $data['first_name'].' '.$data['last_name'];
        return $data;
    }
    protected function doBeforeSuccessResponse(): void
    {
        $data = $this->getDataHandle();
        $email = $data['email'];
        $otp = rand(1000, 9999);

        EmailOtp::where('email', $email)->delete(); // Clear old OTPs

        EmailOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
        $this->otp = $otp;
        Mail::to($email)->send(new SendOtpMail($otp));
    }
    protected function arrayData()
    {
        return array_merge( parent::arrayData(), ['otp' => $this->otp]);
    }
}
