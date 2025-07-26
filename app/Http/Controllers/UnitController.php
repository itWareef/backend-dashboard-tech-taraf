<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Project\Project;
use App\Models\Project\Unit;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UnitController extends Controller
{
    public function listForCustomer()
    {
        $data = QueryBuilder::for(Unit::class)
            ->with('project.guarantees')
                ->where('owner_id' , auth('customer')->id())
                ->allowedFilters([
                    AllowedFilter::partial('project_id')
                ])
            ->get();
        return Response::success(['data' => $data]);
    }
    public function listProjectsForCustomer()
    {
        $data = QueryBuilder::for(Project::class)
            ->whereHas('units', function($q){
                $q->where('owner_id' , auth('customer')->id());
            })
            ->get();
        return Response::success(['data' => $data]);
    }
}
