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
        Schema::create('feature_brand', function (Blueprint $table) {
            $table->foreignId('feature_id')->constrained('features')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['feature_id', 'brand_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_brand');
    }
};
