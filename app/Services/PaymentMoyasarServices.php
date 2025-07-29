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
use App\Models\Store\Order;
use App\Services\InvoiceServices\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PaymentMoyasarServices extends BasePaymentService implements PaymentManagerInterface
{
    protected  $api_secret;
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->api_secret = env('MOYASAR_SECRET_KEY');
        $this->base_url = env('MOYASAR_BASE_URL');
        $this->header = [
            "accept"       => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic " . base64_encode($this->api_secret . ": ")
        ];
        $this->invoiceService = $invoiceService;
    }

    public function sendPayment(Request $request)
    {
        $data = $request->all();
        $order =Order::find($data['order_id']);
        $data['amount'] = $order->total_price * 100;
        $data['description'] = "Pay Order #" . $order->id;
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
         $data['callback_url'] ='http://api.taraf.dashboard-tech.com/';
        $response = $this->buildRequest("POST",'/v1/payments',$data);
        if ($response->getData(true)['status'] === 'success') {
            $order->payment_status = 'paid';
            $order->save();
            
            // Update invoice status to paid
            $this->invoiceService->updateInvoiceStatusOnPayment($order);
            
            return Response::success(['Transaction_url' => $response->getData(true)['data']['source']['transaction_url'] ], ["Redirect To This Link To Confirm Payment"]);
        }else{
            $order->payment_status = 'rejected';
            $order->save();
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
