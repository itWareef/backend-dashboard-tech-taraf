<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceRequests\AddReviewRequest;
use App\Http\Requests\StoreMaintenanceRequestRequest;
use App\Http\Requests\UpdateMaintenanceRequestRequest;
use App\Models\Requests\MaintenanceRequest;
use App\Services\RequestsServices\MaintenanceRequestStoringService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class MaintenanceRequestController extends Controller
{
    public function store()
    {
        return (new MaintenanceRequestStoringService())->storeNewRecord();
    }

    public function list(string $status)
    {
        $data = QueryBuilder::for(MaintenanceRequest::class)
            ->with(['unit','project','requester'])
            ->where('status', $status)
            ->where('requester_id', auth('customer')->id())
            ->get();
        return response(['data' => $data]);
    }

    public function addReview(AddReviewRequest$request,MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->update([
            'status'    => MaintenanceRequest::FINISHED,
            'rating' => $request->input('rating'),
            'notes' => $request->input('notes')?? $maintenanceRequest->notes
        ]);
        return Response::success([],['شكرا لإضاقة مراجعة نقدر وقتك الثمين']);
    }
}
