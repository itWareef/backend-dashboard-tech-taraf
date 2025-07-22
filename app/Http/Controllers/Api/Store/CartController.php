<?php
namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Cart;
use App\Models\Store\Brand;
use App\Models\Store\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::with('items.brand')
            ->where('customer_id', $request->user('customer')->id)
            ->get();

        return Response::success($cart->toArray());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $customer = $request->user('customer');

        // Get or create cart for the customer
        $cart = Cart::firstOrCreate([
            'customer_id' => $customer->id,
        ]);

        // Update or create the cart item
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('brand_id', $data['brand_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'brand_id' => $data['brand_id'],
                'quantity' => $data['quantity'],
            ]);
        }

        return Response::success([], ['تمت إضافة المنتج إلى السلة بنجاح']);
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $customer = $request->user('customer');

        // Check if the cart item belongs to the customer's cart
        if ($cartItem->cart->customer_id !== $customer->id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return Response::success([], ['تم تعديل الكمية بنجاح']);
    }
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return Response::success([],['تم حذف  السلة بنجاح']);
    }
    public function deleteIem(CartItem $cartItem)
    {
        $cartItem->delete();

        return Response::success([],['تم حذف من السلة بنجاح']);
    }
}
