<?php

namespace App\Http\Controllers;



use App\Models\Category;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function listMaintenance()
    {
        $data = QueryBuilder::for(Category::class)
            ->where('type' , Category::MAINTENANCE)->get(['id', 'name'])->toArray();
        return Response::success($data);
    }
    public function listPlanting()
    {
        $data = QueryBuilder::for(Category::class)
            ->where('type' , Category::PLANTING)->get(['id', 'name'])->toArray();
        return Response::success($data);
    }
}
