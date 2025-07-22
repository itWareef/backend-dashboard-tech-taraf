<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Requests\ValidateCouponRequest;
use App\Models\Store\Coupon;
use App\Services\Store\CouponServices\CouponDeletingService;
use App\Services\Store\CouponServices\CouponStoringService;
use App\Services\Store\CouponServices\CouponUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CouponController
{
    public function index()
    {
        return response()->json(QueryBuilder::for(Coupon::class)->paginate(20));
    }
    public function store()
    {
     return (new CouponStoringService())->storeNewRecord();
    }
    public function update(Coupon $coupon)
    {
        return (new CouponUpdatingService($coupon))->update();
    }
    public function destroy(Coupon $coupon)
    {
        return (new CouponDeletingService($coupon))->delete();
    }
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        $message = $coupon->is_active ? 'تم تفعيل الكوبون' : 'تم تعطيل الكوبون';
        return Response::success($coupon, [$message]);
    }

    public function validateCoupon(ValidateCouponRequest $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return Response::error('الكوبون غير صالح أو منتهي',400);
        }

        return Response::success([
            'discount_percentage' => $coupon->discount_percentage,
            'discount_value' => $coupon->calculateDiscount($request->input('total', 0)),
            'coupon_id' => $coupon->id,
        ], ['الكوبون صالح']);
    }
}
