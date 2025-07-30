<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Services\InvoiceServices\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Get all invoices (admin)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Invoice::with(['customer', 'invoiceable']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by invoiceable type
        if ($request->has('invoiceable_type')) {
            $query->where('invoiceable_type', $request->invoiceable_type);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    /**
     * Get customer invoices summary (paid and unpaid amounts)
     */
    public function getCustomerInvoicesSummary(Request $request): JsonResponse
    {
        $customerId = $request->user('customer')->id ?? $request->customer_id;
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'معرف العميل مطلوب'
            ], 400);
        }

        $summary = $this->invoiceService->getCustomerInvoicesSummary($customerId);

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Get all customer invoices
     */
    public function getCustomerInvoices(Request $request): JsonResponse
    {
        $customerId = $request->user()->id ?? $request->customer_id;
        $status = $request->get('status'); // paid or unpaid
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'معرف العميل مطلوب'
            ], 400);
        }

        $invoices = $this->invoiceService->getCustomerInvoices($customerId, $status);

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    /**
     * Get customer paid invoices
     */
    public function getCustomerPaidInvoices(Request $request): JsonResponse
    {
        $customerId = $request->user()->id ?? $request->customer_id;
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'معرف العميل مطلوب'
            ], 400);
        }

        $invoices = $this->invoiceService->getCustomerInvoices($customerId, 'paid');

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    /**
     * Get customer unpaid invoices
     */
    public function getCustomerUnpaidInvoices(Request $request): JsonResponse
    {
        $customerId = $request->user()->id ?? $request->customer_id;
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'معرف العميل مطلوب'
            ], 400);
        }

        $invoices = $this->invoiceService->getCustomerInvoices($customerId, 'unpaid');

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    /**
     * Get specific invoice details
     */
    public function show(int $id): JsonResponse
    {
        $invoice = Invoice::with(['customer', 'invoiceable'])->find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
    }

    /**
     * Create a new invoice
     */
    public function store(InvoiceRequest $request): JsonResponse
    {
        try {
            // The validation in InvoiceRequest already handles model existence and duplicate checks
            $modelClass = $request->invoiceable_type;
            $model = $modelClass::find($request->invoiceable_id);

            $invoice = $this->invoiceService->createInvoice(
                $model,
                $request->amount,
                $request->description
            );

            // Load relationships for response
            $invoice->load(['customer', 'invoiceable']);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الفاتورة بنجاح',
                'data' => $invoice
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الفاتورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice details
     */
    public function update(int $id, InvoiceRequest $request): JsonResponse
    {
        $invoice = Invoice::with(['customer', 'invoiceable'])->find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة'
            ], 404);
        }

        try {
            // Check if trying to change invoiceable relationship
            if ($request->has('invoiceable_type') || $request->has('invoiceable_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تغيير نوع الطلب المرتبط بالفاتورة'
                ], 400);
            }

            // Update allowed fields
            $invoice->update([
                'amount' => $request->amount,
                'description' => $request->description,
                'status' => $request->status ?? $invoice->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الفاتورة بنجاح',
                'data' => $invoice->fresh(['customer', 'invoiceable'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الفاتورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice status
     */
    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:paid,unpaid'
        ]);

        $invoice = Invoice::with(['customer', 'invoiceable'])->find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة'
            ], 404);
        }

        try {
            if ($request->status === 'paid') {
                $this->invoiceService->markAsPaid($invoice);
            } else {
                $this->invoiceService->markAsUnpaid($invoice);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الفاتورة بنجاح',
                'data' => $invoice->fresh(['customer', 'invoiceable'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الفاتورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete invoice
     */
    public function destroy(int $id): JsonResponse
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة'
            ], 404);
        }

        try {
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الفاتورة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الفاتورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
