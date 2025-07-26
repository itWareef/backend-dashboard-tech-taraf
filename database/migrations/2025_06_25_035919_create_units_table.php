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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('villa_number')->nullable();
            $table->string('deed_number')->nullable();
            $table->string('number')->nullable();
            $table->string('space')->nullable();
            $table->date('purchase_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
