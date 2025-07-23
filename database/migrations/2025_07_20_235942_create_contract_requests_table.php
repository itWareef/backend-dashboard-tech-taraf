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
        Schema::create('contract_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('developer')->nullable(); // المطور (اختياري)
            $table->string('request_number')->nullable(); // المطور (اختياري)
            $table->string('project')->nullable(); // المشروع (اختياري)
            $table->string('property_type'); // نوع العقار
            $table->string('property_age')->nullable(); // عمر العقار
            $table->string('area')->nullable(); // المساحة
            $table->string('unit_number')->nullable(); // رقم الوحدة
            $table->string('ownership_number')->nullable(); // رقم الملك
            $table->string('latitude')->nullable(); // الموقع الجغرافي
            $table->string('longitude')->nullable(); // الموقع الجغرافي
            $table->string('contract_type'); // نوع العقد
            $table->string('payment_status')->default('لم يتم الدفع'); // حالة السداد
            $table->string('request_status')->default('لم يتم الرد');  // حالة الطلب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_requests');
    }
};
