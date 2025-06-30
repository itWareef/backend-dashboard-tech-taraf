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
        Schema::create('planting_request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planting_id')->constrained('planting_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planting_request_attachments');
    }
};
