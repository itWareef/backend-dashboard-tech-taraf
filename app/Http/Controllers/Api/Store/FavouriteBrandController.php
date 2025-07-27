<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Brand;
use App\Models\Store\BrandFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FavouriteBrandController extends Controller
{
    public function index(Request $request)
    {
        $favourites = $request->user('customer')
            ->favouriteBrands()
            ->get()->toArray();

        return Response::success($favourites);
    }

    public function toggle(Request $request, Brand $brand)
    {
        $customer = $request->user('customer');

        $exists = BrandFavourite::where('customer_id', $customer->id)
            ->where('brand_id', $brand->id)
            ->first();

        if ($exists) {
            $exists->delete();
            return Response::success([], ['تم إزالة المنتج من المفضلة']);
        }

        BrandFavourite::create([
            'customer_id' => $customer->id,
            'brand_id'    => $brand->id
        ]);

        return Response::success([], ['تم إضافة المنتج للمفضلة']);
    }
}
