<?php

namespace App\Services;

use App\Models\Customer\Customer;
use App\Models\Invoice;
use App\Models\Project\Unit;
use App\Models\RequestMaintenanceAndService\ContractRequest;
use App\Models\RequestMaintenanceAndService\GardenRequest;
use App\Models\RequestMaintenanceAndService\ServiceRequest;
use App\Models\Requests\MaintenanceRequest;
use App\Models\Requests\PlantingRequest;
use App\Models\Store\Brand;
use App\Models\Store\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NumberingService
{
    /**
     * Generate a unique number for the given model
     */
    public static function generateNumber(string $modelClass): string
    {
        $prefix = self::getPrefixForModel($modelClass);
        $lastNumber = self::getLastNumber($modelClass);
        $nextNumber = $lastNumber + 1;

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the prefix for a model
     */
    private static function getPrefixForModel(string $modelClass): string
    {
        $prefixes = [
            Order::class => 'O',           // فاتورة (Invoice)
            ContractRequest::class => 'CR', // عقد (Contract)
            GardenRequest::class => 'GR',   // طلب حديقة (Garden Request)
            ServiceRequest::class => 'SR',  // خدمة (Service)
            PlantingRequest::class => 'PR', // طلب زراعة (Planting Request)
            MaintenanceRequest::class => 'MR', // طلب صيانة (Maintenance Request)
            Customer::class => 'CU',        // عميل (Customer)
            User::class => 'E',             // موظف (Employee)
            Brand::class => 'P',            // منتج (Product)
            Unit::class => 'U',
            Invoice::class=> 'I'// رقم الوحدة (Unit Number)
        ];

        return $prefixes[$modelClass] ?? 'X';
    }

    /**
     * Get the last number used for a model
     */
    private static function getLastNumber(string $modelClass): int
    {
        $prefix = self::getPrefixForModel($modelClass);

        $lastRecord = DB::table(self::getTableName($modelClass))
            ->where('number', 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(number, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        if (!$lastRecord) {
            return 0;
        }

        $number = $lastRecord->number;
        $numericPart = substr($number, strlen($prefix));

        return (int) $numericPart;
    }

    /**
     * Get the table name for a model
     */
    private static function getTableName(string $modelClass): string
    {
        $model = new $modelClass();
        return $model->getTable();
    }

    /**
     * Generate number for a specific model instance
     */
    public static function generateNumberForModel($model): string
    {
        return self::generateNumber(get_class($model));
    }
}
