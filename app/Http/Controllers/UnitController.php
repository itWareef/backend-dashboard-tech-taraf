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
        $units = QueryBuilder::for(Unit::class)
            ->with(['project.guarantees.supplier', 'project.developer'])
            ->where('owner_id', auth('customer')->id())
            ->allowedFilters([
                AllowedFilter::partial('project_id')
            ])
            ->get();
    
        // Inject unit purchase_date to each guarantee so expiry_date can be calculated
        $units->each(function ($unit) {
            $purchaseDate = $unit->purchase_date;
    
            $unit->project->guarantees->each(function ($guarantee) use ($purchaseDate) {
                $guarantee->unit_purchase_date = $purchaseDate; // Needed for accessor
            });
        });
    
        return Response::success(['data' => $units]);
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
