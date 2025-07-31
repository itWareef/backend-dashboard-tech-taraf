<?php

namespace App\Http\Controllers;



use App\Models\Invoice;
use App\Models\Store\Order;
use App\Services\PaymentMoyasarServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


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
    public function paymentProcessInvoice(Request $request)
    {
        return (new PaymentMoyasarServices())->sendPaymentInvoice($request);
    }
    public function orderPayed(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->payment_status = 'paid';
        $invoice = $order->invoice;
        if ($invoice && $order->payment_status === 'paid') {
            $invoice->markAsPaid();
        }
        return Response::success([],['paid successfully']);
    }
    public function invoicePayed(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $invoice->status = 'paid';
        return Response::success([],['paid successfully']);
    }
}
