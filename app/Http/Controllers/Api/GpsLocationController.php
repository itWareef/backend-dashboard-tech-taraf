<?php

namespace App\Http\Controllers\Api;

use App\Events\GpsLocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\GpsLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class GpsLocationController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = GpsLocation::updateOrCreate(
            ['supervisor_id' => auth('supervisor')->id()],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'recorded_at' => now(),
            ]
        );
        $location->load('supervisor');
        broadcast(new GpsLocationUpdated($location))->toOthers();

        return Response::success([],['done']);
    }
    public function index()
    {
        $data = QueryBuilder::for(GpsLocation::class)->with('supervisor')->get()->toArray();
        return Response::success($data);
    }
}
