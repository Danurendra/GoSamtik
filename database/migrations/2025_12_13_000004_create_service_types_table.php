<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g., "General Waste", "Recyclables"
            $table->string('slug')->unique();                // URL-friendly name
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);            // Base price per collection
            $table->string('icon')->nullable();              // Icon class or image path
            $table->string('color')->default('#22c55e');     // Display color (green default)
            $table->json('requirements')->nullable();        // Special requirements
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};