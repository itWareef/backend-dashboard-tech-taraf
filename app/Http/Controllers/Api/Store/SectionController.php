<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Store\Section;
use App\Services\Store\SectionServices\SectionDeletingService;
use App\Services\Store\SectionServices\SectionStoringService;
use App\Services\Store\SectionServices\SectionUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class SectionController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(Section::class)->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new SectionStoringService())->storeNewRecord();
    }
    public function destroy(Section $section){
        return (new SectionDeletingService($section))->delete();
    }

    public function update( Section $section){
        return (new SectionUpdatingService($section))->update();
    }
    public function list()
    {
        $data = QueryBuilder::for(Section::class)->get()->toArray();
        return Response::success($data);
    }
}
