<?php

use App\Models\Requests\SuperVisorRequests;
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
        Schema::create('spervisor_maintenance_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('maintenance_id')->constrained('maintenance_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status',SuperVisorRequests::STATUES)->default(SuperVisorRequests::PENDING);
            $table->string('picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spervisor_maintenance_request');
    }
};
