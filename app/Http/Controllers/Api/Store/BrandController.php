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
        $data = QueryBuilder::for(Brand::class)->with(['features','section'])->paginate(20);

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
    public function show( Brand $brand){
        return Response::success($brand->load(['features','section'])->toArray());
    }
    public function list()
    {
        $data = QueryBuilder::for(Brand::class)->get()->toArray();
        return Response::success($data);
    }
}
