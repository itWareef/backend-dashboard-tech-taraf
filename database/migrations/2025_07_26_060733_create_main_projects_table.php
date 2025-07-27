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
        Schema::create('main_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('developer_id')->constrained('main_developers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('low_price');
            $table->string('high_price');
            $table->string('unit_count');
            $table->string('price_precentage');
            $table->string('youtube_link');
            $table->string('low_space');
            $table->string('high_space');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_projects');
    }
};
