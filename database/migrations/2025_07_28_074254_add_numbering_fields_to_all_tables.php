<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'orders',
            'contract_requests',
            'garden_requests',
            'service_requests',
            'planting_requests',
            'maintenance_requests',
            'customers',
            'users',
            'brands',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasColumn($tableName, 'number')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->string('number')->unique()->nullable()->after('id');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'orders',
            'contract_requests',
            'garden_requests',
            'service_requests',
            'planting_requests',
            'maintenance_requests',
            'customers',
            'users',
            'brands',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'number')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('number');
                });
            }
        }
    }
};
