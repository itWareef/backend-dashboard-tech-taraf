<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Services\DeveloperServices\DeveloperDeletingService;
use App\Services\DeveloperServices\DeveloperStoringService;
use App\Services\DeveloperServices\DeveloperUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class DeveloperController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(Developer::class)->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new DeveloperStoringService())->storeNewRecord();
    }
    public function destroy(Developer $developer){
        return (new DeveloperDeletingService($developer))->delete();
    }

    public function update( Developer $developer){
        return (new DeveloperUpdatingService($developer))->update();
    }
    public function list()
    {
        $data = QueryBuilder::for(Developer::class)->get()->toArray();
        return Response::success($data);
    }
}
