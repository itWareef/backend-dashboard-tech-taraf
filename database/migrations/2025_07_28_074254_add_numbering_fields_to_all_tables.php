<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add number to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to contract_requests table
        Schema::table('contract_requests', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to garden_requests table
        Schema::table('garden_requests', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to planting_requests table
        Schema::table('planting_requests', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to maintenance_requests table
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

        // Add number to brands table
        Schema::table('brands', function (Blueprint $table) {
            $table->string('number')->unique()->nullable()->after('id');
        });

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove number from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from contract_requests table
        Schema::table('contract_requests', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from garden_requests table
        Schema::table('garden_requests', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from planting_requests table
        Schema::table('planting_requests', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from maintenance_requests table
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('number');
        });

        // Remove number from brands table
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('number');
        });


    }
};
