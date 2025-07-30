<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Store\CartItem;
use App\Models\Store\Coupon;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.brand')
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            try {
                $user = auth('customer')->user();

                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'يجب تسجيل الدخول أولاً'
                    ], 401);
                }

                // Get cart items
                $cartItems = CartItem::with('brand')
                    ->whereHas('cart', function ($query) use ($user) {
                        $query->where('customer_id', $user->id);
                    })->get();

                if ($cartItems->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'السلة فارغة'
                    ], 400);
                }

                // Calculate total
                $total = $cartItems->sum(fn($item) => $item->brand->price * $item->quantity);
                $discountAmount = 0;
                $couponId = null;

                // Validate coupon
                if ($request->coupon_code) {
                    $coupon = Coupon::where('code', $request->coupon_code)->first();

                    if ($coupon && $coupon->isValid()) {
                        $discountAmount = $coupon->calculateDiscount($total);
                        $couponId = $coupon->id;
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'كود الكوبون غير صالح أو منتهي'
                        ], 400);
                    }
                }

                $order = Order::create([
                    'customer_id'    => $user->id,
                    'total_price'    => $total - $discountAmount,
                    'coupon_id'      => $couponId,
                    'discount_amount'=> $discountAmount,
                    'payment_method'=> $request->input('payment_method'),
                    'order_status'   => 'pending',
                    'payment_status' => 'unpaid',
                    'customer_name'  => $request->customer_name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'address'        => $request->address,
                    'day'            => $request->day,
                    'date'           => Carbon::now()->addDays(4)->toDateString(),
                ]);

                // Save order items
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'  => $order->id,
                        'brand_id'  => $item->brand_id,
                        'quantity'  => $item->quantity,
                        'price'     => $item->brand->price,
                    ]);
                }

                // Clear user's cart
                CartItem::whereHas('cart', function ($q) use ($user) {
                    $q->where('customer_id', $user->id);
                })->delete();

                // Load relationships for response
                $order->load(['items.brand', 'customer', 'coupon']);

                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الطلب بنجاح',
                    'data' => [
                        'id' => $order->id,
                        'date' => $order->date,
                        'total_price' => $order->total_price,
                        'order_status' => $order->order_status,
                        'payment_status' => $order->payment_status
                    ]
                ], 201);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الطلب',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }

    public function update(int $id, OrderRequest $request): JsonResponse
    {
        $order = Order::with(['items.brand', 'customer', 'coupon'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود'
            ], 404);
        }

        // Check if user owns this order
        if ($order->customer_id !== auth('customer')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا الطلب'
            ], 403);
        }

        // Only allow updates for pending orders
        if ($order->order_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تعديل الطلب بعد تأكيده'
            ], 400);
        }

        try {
            $order->update([
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'address'        => $request->address,
                'day'            => $request->day,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الطلب بنجاح',
                'data' => $order->fresh(['items.brand', 'customer', 'coupon'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الطلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['items.brand', 'customer', 'coupon'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود'
            ], 404);
        }

        // Check if user owns this order
        if ($order->customer_id !== auth('customer')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بعرض هذا الطلب'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
