<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\InvoiceServices\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateInvoiceForOrder implements ShouldQueue
{
    use InteractsWithQueue;

    protected InvoiceService $invoiceService;

    /**
     * Create the event listener.
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        // Create invoice automatically when order is created
        $this->invoiceService->createInvoiceForOrder($event->order);
    }
}
