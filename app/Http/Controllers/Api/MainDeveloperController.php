<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainDeveloper;
use App\Services\MainDeveloperServices\MainDeveloperService;
use App\Services\MainDeveloperServices\MainDeveloperDeletingService;
use App\Services\MainDeveloperServices\MainDeveloperUpdatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class MainDeveloperController extends Controller
{
    /**
     * Display a listing of main developers.
     */
    public function index()
    {
        $data = QueryBuilder::for(MainDeveloper::class)->with('projects')->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created main developer.
     */
    public function store()
    {
        return (new MainDeveloperService())->storeNewRecord();
    }

    /**
     * Display the specified main developer.
     */
    public function show(MainDeveloper $mainDeveloper)
    {
        $mainDeveloper->load('projects');
        return Response::success($mainDeveloper->toArray());
    }

    /**
     * Update the specified main developer.
     */
    public function update(MainDeveloper $mainDeveloper)
    {
        return (new MainDeveloperUpdatingService($mainDeveloper))->update();
    }

    /**
     * Remove the specified main developer.
     */
    public function destroy(MainDeveloper $mainDeveloper)
    {
        return (new MainDeveloperDeletingService($mainDeveloper))->delete();
    }

    /**
     * Get list of all main developers.
     */
    public function list()
    {
        $data = QueryBuilder::for(MainDeveloper::class)->withCount('projects')->withSum('projects', 'unit_count')->get()->toArray();
        return Response::success($data);
    }
} 