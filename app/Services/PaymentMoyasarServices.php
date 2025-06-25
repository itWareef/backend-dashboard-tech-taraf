<?php

namespace App\Services;

use App\Core\Interfaces\Payment\PaymentManagerInterface;
use App\Jobs\UserSendCvJob;
use App\Models\AdminPanel\Bank\Transaction;
use App\Models\AdminPanel\Packages\Coupon;
use App\Models\AdminPanel\Packages\Package;
use App\Models\AdminPanel\Plan\Plan;
use App\Models\AdminPanel\Subscription\Subscription;
use App\Models\AuthenticationModule\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PaymentMoyasarServices extends BasePaymentService implements PaymentManagerInterface
{
    protected  $api_secret;


    public function __construct()
    {
        $this->api_secret = env('MOYASAR_SECRET_KEY');
        $this->base_url = env('MOYASAR_BASE_URL');
        $this->header = [
            "accept"       => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic " . base64_encode($this->api_secret . ": ")
        ];
    }

    public function sendPayment(Request $request)
    {
        $data = $request->all();
        $package =Plan::find($data['plan_id']);
        $data['amount'] = $package->price * 100;
        $data['description'] = "Subscribe Plan";
        $data['currency'] = "SAR";
        $data['source'] =[
        'type' => $request->card,
        'name' => $request->card_holder_name,
        'number' => $request->card_number,
        'cvc' => $request->card_cvc,
        'month' => $request->card_expiry_month,
        'year' => $request->card_expiry_year,
        ];
         $data['success_url'] =$request->getSchemeAndHttpHost().'/api/payment/callback';
         $data['callback_url'] ='https://pt-express.net/';
        $response = $this->buildRequest("POST",'/v1/payments',$data);
        if ($response->getData(true)['status'] === 'success') {
            $finalAmount =$package->price;
            $durations = [
                'days' => $package->duration_days ?? 0,
                'months' => $package->duration_months ?? 0,
                'years' => $package->duration_years ?? 0,
            ];

            $expireDate = now();

            foreach ($durations as $unit => $value) {
                if ($value > 0) {
                    $expireDate = $expireDate->{"add" . ucfirst($unit)}($value);
                }
            }
            Transaction::create([
                'sender_id' => auth('vendor')->id() ?? null,
                'sender_type' => 'vendor',
                'plan_id'=>$data['plan_id'],
                'expire_date'=>$expireDate,
                'amount'=> $finalAmount,
            ]);
            return Response::success(['Transaction_url' => $response->getData(true)['data']['source']['transaction_url'] ], ["Redirect To This Link To Confirm Payment"]);
        }else{
            return Response::error($response->getData(true)['errors']['errors']);
        }
    }

    public function callBack(Request $request)
    {
        $responseStatus = $request->get('status');

        if (isset($responseStatus) && $responseStatus == 'paid')     {
            return true;
        }
        return false;

    }
}
