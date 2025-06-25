<?php

namespace App\Core\Interfaces\Payment;

use Illuminate\Http\Request;

interface PaymentManagerInterface
{
    public function sendPayment(Request $request);
    public function callBack(Request $request);
}
