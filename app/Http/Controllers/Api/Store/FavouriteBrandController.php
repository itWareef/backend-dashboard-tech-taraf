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
        $customer = $request->user('customer');

        // Get favorite brands
        $favourites = $customer->favouriteBrands()->with('products')->get();

        // Get all brand_ids that exist in the customer's cart (via product.brand_id)
        $brandIdsInCart = collect();

        if ($customer->cart) {
            $brandIdsInCart = $customer->cart->items()
                ->whereHas('product.brand') // Ensure product and brand exist
                ->with('product.brand:id') // Optional optimization
                ->get()
                ->pluck('product.brand_id')
                ->unique();
        }

        // Append in_cart flag to each favorite brand
        $favourites->transform(function ($brand) use ($brandIdsInCart) {
            $brand->in_cart = $brandIdsInCart->contains($brand->id);
            return $brand;
        });

        return Response::success($favourites->toArray());
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
            'brand_id' => $brand->id
        ]);

        return Response::success([], ['تم إضافة المنتج للمفضلة']);
    }
}
