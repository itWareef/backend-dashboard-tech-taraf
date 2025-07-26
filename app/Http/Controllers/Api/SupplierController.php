<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Supplier;
use App\Services\SupplierServices\SupplierDeletingService;
use App\Services\SupplierServices\SupplierStoringService;
use App\Services\SupplierServices\SupplierUpdatingService;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class SupplierController extends Controller
{
    public function index(){
        $data = QueryBuilder::for(Supplier::class)->paginate(20);

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(){
        return (new SupplierStoringService())->storeNewRecord();
    }
    public function destroy(Supplier $supplier){
        return (new SupplierDeletingService($supplier))->delete();
    }

    public function update( Supplier $supplier){
        return (new SupplierUpdatingService($supplier))->update();
    }
    public function list()
    {
        $data = QueryBuilder::for(Supplier::class)->get()->toArray();
        return Response::success($data);
    }
}
