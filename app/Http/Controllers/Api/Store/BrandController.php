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
    public function index(){
        $data = QueryBuilder::for(Brand::class)->with(['features','section','pictures'])->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new BrandStoringService())->storeNewRecord();
    }
    public function destroy(Brand $brand){
        return (new BrandDeletingService($brand))->delete();
    }

    public function update( Brand $brand){
        return (new BrandUpdatingService($brand))->update();
    }
    public function show(Brand $brand)
    {
        $customer = auth('customer')->user();
    
        // Check if the brand is in the customer's favourites
        $isFavorite = $customer
            ? $customer->favouriteBrands()->where('brand_id', $brand->id)->exists()
            : false;
    
        $brand->load(['features', 'section', 'pictures']);
    
        // Add is_favorite to the response
        $data = $brand->toArray();
        $data['is_favorite'] = $isFavorite;
    
        return Response::success($data);
    }
    
    public function list()
    {
        $data = QueryBuilder::for(Brand::class)->get()->toArray();
        return Response::success($data);
    }
}
