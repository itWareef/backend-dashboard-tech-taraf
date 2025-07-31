<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Brand;
use App\Services\Store\BrandServices\BrandDeletingService;
use App\Services\Store\BrandServices\BrandStoringService;
use App\Services\Store\BrandServices\BrandUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class BrandController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Brand::class)->with(['features', 'section', 'pictures'])->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store()
    {
        return (new BrandStoringService())->storeNewRecord();
    }
    public function destroy(Brand $brand)
    {
        return (new BrandDeletingService($brand))->delete();
    }

    public function update(Brand $brand)
    {
        return (new BrandUpdatingService($brand))->update();
    }
    public function show(Brand $brand)
    {
        $customer = auth('customer')->user()??false;

        // Check if brand is in customer's favorites
        $isFavorite = $customer
            ? $customer->favouriteBrands()->where('id', $brand->id)->exists()
            : false;

        $isInCart = false;
        // Check if the brand exists in any of the customer's cart items
            if ($customer && $customer->cart) {
                $isInCart = $customer->cart->items()
                    ->whereHas('brand', function ($query) use ($brand) {
                        $query->where('id', $brand->id);
                    })->exists();
            }

        // Eager load relationships
        $brand->load(['features', 'section', 'pictures']);

        // Convert brand to array and append flags
        $data = $brand->toArray();
        $data['is_favorite'] = $isFavorite;
        $data['in_cart'] = $isInCart;

        return Response::success($data);
    }


    public function list()
    {
        $data = QueryBuilder::for(Brand::class)->get()->toArray();
        return Response::success($data);
    }
}
