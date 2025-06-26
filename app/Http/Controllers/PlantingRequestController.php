<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceRequests\AddReviewRequest;
use App\Http\Requests\StorePlantingRequestRequest;
use App\Http\Requests\UpdatePlantingRequestRequest;
use App\Models\Requests\PlantingRequest;
use App\Services\RequestsServices\PlantingRequestStoringService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class PlantingRequestController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return (new PlantingRequestStoringService())->storeNewRecord();
    }

    public function list(string $status)
    {
        $data = QueryBuilder::for(PlantingRequest::class)
            ->where('status', $status)
            ->paginate(7);
        return response(['data' => $data]);
    }

    public function addReview(AddReviewRequest$request,PlantingRequest $plantingRequest)
    {
        $plantingRequest->update([
            'status'    => PlantingRequest::FINISHED,
            'rating' => $request->input('rating'),
            'notes' => $request->input('notes')?? $plantingRequest->notes
        ]);
        return Response::success([],['شكرا لإضاقة مراجعة نقدر وقتك الثمين']);
    }
}
