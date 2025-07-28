<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainProject;
use App\Models\MainProjectPicture;
use App\Services\MainProjectServices\MainProjectService;
use App\Services\MainProjectServices\MainProjectDeletingService;
use App\Services\MainProjectServices\MainProjectUpdatingService;
use App\Core\Classes\HandleFiles\StoragePictures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class MainProjectController extends Controller
{
    /**
     * Display a listing of main projects.
     */
    public function index()
    {
        $data = QueryBuilder::for(MainProject::class)->with(['developer', 'pictures'])->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created main project.
     */
    public function store()
    {
        return (new MainProjectService())->storeNewRecord();
    }

    /**
     * Display the specified main project.
     */
    public function show(MainProject $mainProject)
    {
        $mainProject->load(['developer', 'pictures']);
        return Response::success($mainProject->toArray());
    }

    /**
     * Update the specified main project.
     */
    public function update(MainProject $mainProject)
    {
        return (new MainProjectUpdatingService($mainProject))->update();
    }

    /**
     * Remove the specified main project.
     */
    public function destroy(MainProject $mainProject)
    {
        return (new MainProjectDeletingService($mainProject))->delete();
    }

    /**
     * Remove a specific picture from a project.
     */
    public function deletePicture(MainProject $mainProject, MainProjectPicture $picture)
    {
        $picture->delete();

        return Response::success([], ['تم حذف الصورة بنجاح']);
    }

    /**
     * Get list of all main projects.
     */
    public function list()
    {
        $data = QueryBuilder::for(MainProject::class)->with(['developer', 'pictures'])->get()->toArray();
        return Response::success($data);
    }
    public function listPictures()
    {
        $data = QueryBuilder::for(MainProjectPicture::class)->get()->toArray();
        return Response::success($data);
    }
} 