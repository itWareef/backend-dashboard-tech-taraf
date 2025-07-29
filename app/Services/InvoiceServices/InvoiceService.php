<?php

namespace App\Services\InvoiceServices;

use App\Models\Invoice;
use App\Models\Store\Order;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Create an invoice for a request or order
     */
    public function createInvoice($model, float $amount, ?string $description = null): Invoice
    {
        return DB::transaction(function () use ($model, $amount, $description) {
            return Invoice::create([
                'customer_id' => $model->customer_id ?? $model->requester_id,
                'amount' => $amount,
                'status' => Invoice::UNPAID,
                'description' => $description,
                'invoiceable_type' => get_class($model),
                'invoiceable_id' => $model->id,
            ]);
        });
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice): bool
    {
        return $invoice->markAsPaid();
    }

    /**
     * Mark invoice as unpaid
     */
    public function markAsUnpaid(Invoice $invoice): bool
    {
        return $invoice->markAsUnpaid();
    }

    /**
     * Get customer invoices summary
     */
    public function getCustomerInvoicesSummary(int $customerId): array
    {
        $paidInvoices = Invoice::where('customer_id', $customerId)
            ->paid()
            ->sum('amount');

        $unpaidInvoices = Invoice::where('customer_id', $customerId)
            ->unpaid()
            ->sum('amount');

        return [
            'paid' => $paidInvoices,
            'unpaid' => $unpaidInvoices,
        ];
    }

    /**
     * Get all customer invoices
     */
    public function getCustomerInvoices(int $customerId, ?string $status = null)
    {
        $query = Invoice::where('customer_id', $customerId)
            ->with(['customer', 'invoiceable']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create invoice automatically when order is created
     */
    public function createInvoiceForOrder(Order $order): Invoice
    {
        return $this->createInvoice($order, $order->total_price, "فاتورة طلب رقم: {$order->number}");
    }

    /**
     * Update invoice status when payment is completed
     */
    public function updateInvoiceStatusOnPayment(Order $order): void
    {
        $invoice = $order->invoice;
        if ($invoice && $order->payment_status === 'paid') {
            $this->markAsPaid($invoice);
        }
    }
} 