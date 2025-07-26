<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Guarantee;
use App\Models\Supplier;
use App\Services\GuaranteeServices\GuaranteeDeletingService;
use App\Services\GuaranteeServices\GuaranteeStoringService;
use App\Services\GuaranteeServices\GuaranteeUpdatingService;
use App\Services\SupplierServices\SupplierDeletingService;
use App\Services\SupplierServices\SupplierStoringService;
use App\Services\SupplierServices\SupplierUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class GuaranteeController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(Guarantee::class)->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new GuaranteeStoringService())->storeNewRecord();
    }
    public function destroy(Guarantee $guarantee){
        return (new GuaranteeDeletingService($guarantee))->delete();
    }

    public function update( Guarantee $guarantee){
        return (new GuaranteeUpdatingService($guarantee))->update();
    }
    public function list()
    {
        $data = QueryBuilder::for(Guarantee::class)->get()->toArray();
        return Response::success($data);
    }
}
