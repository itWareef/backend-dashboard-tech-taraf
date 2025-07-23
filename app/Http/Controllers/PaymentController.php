<?php

namespace App\Http\Controllers;



use App\Services\PaymentMoyasarServices;
use Illuminate\Http\Request;


class PaymentController extends Controller
{

    public function paymentProcess(Request $request)
    {
        return (new PaymentMoyasarServices())->sendPayment($request);
    }
    public function callBack(Request $request)
    {
        return (new PaymentMoyasarServices())->callBack($request);
    }
}
