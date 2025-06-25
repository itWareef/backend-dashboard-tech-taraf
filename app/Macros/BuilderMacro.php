<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

Builder::macro('datesFiltering', function ($column = "created_at") {
    $period_type = request()['period_type'] ?? null;
    $from_date = request()['from_date'] ?? null;
    $to_date = request()['to_date'] ?? null;
    switch ($period_type) {
        case 'day':
            $from = Carbon::parseOrNow($from_date)->startOfDay();
            $to = Carbon::make($from_date)->endOfDay();
            break;
        case 'month':
            $from = Carbon::parseOrNow($from_date)->startOfMonth();
            $to = Carbon::make($from_date)->endOfMonth();
            break;
        case 'quarter':
            $from = Carbon::parseOrNow($from_date)->startOfQuarter();
            $to = Carbon::make($from_date)->endOfQuarter();
            break;
        case 'year':
            $from = Carbon::parseOrNow($from_date)->startOfYear();
            $to = Carbon::make($from_date)->endOfYear();
            break;
        case 'range':
            $from = Carbon::parseOrNow($from_date)->startOfDay();
            $to = Carbon::parseOrNow($to_date)->endOfDay();
            break;
        default:
            break;
    }
    if (isset($from) && isset($to)) {
        $from = $from->format("Y-m-d H:i:s");
        $to = $to->format("Y-m-d H:i:s");
        //return $this->where($column, '>=', $from)->where($column, '<=', $to);
        return $this->whereBetween($column, [$from, $to]);
    }
    return $this;
});

Builder::macro('customOrdering', function ($sortColumn = null, $sort = null) {
    try {
        $requestSortColumn = $sortColumn ?? request()->sortColumn ?? 'id';
        $requestSort = $sort ?? request()->sort ?? 'desc';

        //work only with first level relationships
        if (str_contains($requestSortColumn, '.')) {
            //TODO: based on the length of exploded array will improve the snippet below to handle the multi-level relationships
            [$relation, $column] = explode('.', $requestSortColumn);
            $relationModel = $this->getModel()->{$relation}();
            $relationTable = $relationModel->getModel()->getTable();
            $relationForeignKey = $relationModel->getForeignKeyName();
            $queryTable = $this->from;
            //ordering by relation column
            return $this->leftJoin($relationTable, "$queryTable.$relationForeignKey", "=", "$relationTable.id")
                ->orderBy("$relationTable.$column", $requestSort);
        } else {
            //ordering by column
            return $this->orderBy($requestSortColumn, $requestSort);
        }
    } catch (\Exception $e) {
        //handle exception
    }
    return $this;
});
