<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Feature;
use App\Services\Store\FeatureServices\FeatureDeletingService;
use App\Services\Store\FeatureServices\FeatureStoringService;
use App\Services\Store\FeatureServices\FeatureUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class FeatureController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(Feature::class)->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new FeatureStoringService())->storeNewRecord();
    }
    public function destroy(Feature $feature){
        return (new FeatureDeletingService($feature))->delete();
    }

    public function update( Feature $feature){
        return (new FeatureUpdatingService($feature))->update();
    }
    public function list()
    {
        $data = QueryBuilder::for(Feature::class)->get()->toArray();
        return Response::success($data);
    }
}
