<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Area name
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code_pattern')->nullable(); // Regex for postal codes
            $table->json('boundary_coordinates')->nullable();  // GeoJSON polygon
            $table->decimal('extra_fee', 10, 2)->default(0);  // Additional fee for this area
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_areas');
    }
};