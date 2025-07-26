<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


use App\Models\Project\Project;
use App\Services\ProjectServices\ProjectDeletingService;
use App\Services\ProjectServices\ProjectStoringService;
use App\Services\ProjectServices\ProjectUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(Project::class)->with('guarantees')->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new ProjectStoringService())->storeNewRecord();
    }
    public function destroy(Project $supplier){
        return (new ProjectDeletingService($supplier))->delete();
    }

    public function update( Project $supplier){
        return (new ProjectUpdatingService($supplier))->update();
    }
    public function list()
    {
        $data = QueryBuilder::for(Project::class)->get()->toArray();
        return Response::success($data);
    }
}
