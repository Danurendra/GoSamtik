<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                              // e.g., "Basic Weekly", "Premium Daily"
            $table->string('slug')->unique();
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->integer('frequency_per_week');               // 1-7 times per week
            $table->decimal('monthly_price', 10, 2);             // Total monthly cost
            $table->decimal('per_pickup_price', 10, 2);          // Price per individual pickup
            $table->decimal('discount_percentage', 5, 2)->default(0); // Discount for subscription
            $table->text('description')->nullable();
            $table->json('features')->nullable();                // List of included features
            $table->boolean('is_popular')->default(false);       // Highlight as popular
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};