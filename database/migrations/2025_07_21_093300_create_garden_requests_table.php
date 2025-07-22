<?php

use App\Models\RequestMaintenanceAndService\GardenRequest;
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
        Schema::create('garden_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('unit_type');
            $table->string('space');
            $table->string('location');
            $table->enum('visit_type',GardenRequest::VISIT_TYPES);
            $table->enum('type',GardenRequest::TYPES);
            $table->string('notes')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garden_requests');
    }
};
