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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('date');
            $table->string('picture')->nullable();
            $table->string('time')->nullable();
            $table->string('otp')->nullable();
            $table->text('notes')->nullable();
            $table->double('rating')->nullable();
            $table->integer('visits_count')->default(0);
            $table->enum('status', \App\Models\Requests\MaintenanceRequest::STATUSES)->default(\App\Models\Requests\MaintenanceRequest::IN_PROGRESS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
