<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Store\CartItem;
use App\Models\Store\Coupon;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use Carbon\Carbon;
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

    public function store(OrderRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = auth('customer')->user();

            // Get cart items
            $cartItems = CartItem::with('brand')
                ->whereHas('cart', function ($query) use ($user) {
                    $query->where('customer_id', $user->id);
                })->get();

            if ($cartItems->isEmpty()) {
                return Response::error('السلة فارغة', 400);
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
                    return Response::error('كود الكوبون غير صالح أو منتهي', 400);
                }
            }

            $order = Order::create([
                'customer_id'    => $user->id,
                'total_price'    => $total - $discountAmount,
                'coupon_id'      => $couponId,
                'discount_amount'=> $discountAmount,
                'payment_method'=> $request->input('method'),
                'order_status'   => 'pending',
                'payment_status' => 'unpaid',
                'customer_name'  => $request->customer_name,
                'customer_email'          => $request->email,
                'customer_phone'          => $request->phone,
                'address'        => $request->address,
                'day'        => $request->day,
                'date' => Carbon::now()->addDays(4)->toDateString(),
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

            return Response::success(['id' => $order->id , 'date' => $order->date], ['تم إنشاء الطلب بنجاح']);
        });
    }
}
