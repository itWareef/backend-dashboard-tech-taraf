<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\AdvertisingPost;
use App\Services\Store\AdvertisingPostServices\AdvertisingPostDeletingService;
use App\Services\Store\AdvertisingPostServices\AdvertisingPostStoringService;
use App\Services\Store\AdvertisingPostServices\AdvertisingPostUpdatingService;

use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class AdvertisingPostController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(AdvertisingPost::class)->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new AdvertisingPostStoringService())->storeNewRecord();
    }
    public function destroy(AdvertisingPost $advertisingPost){
        return (new AdvertisingPostDeletingService($advertisingPost))->delete();
    }

    public function update( AdvertisingPost $advertisingPost){
        return (new AdvertisingPostUpdatingService($advertisingPost))->update();
    }

    public function list()
    {
        $data = QueryBuilder::for(AdvertisingPost::class)->get()->toArray();
        return Response::success($data);
    }
}
