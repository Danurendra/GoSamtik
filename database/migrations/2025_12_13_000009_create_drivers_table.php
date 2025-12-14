<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->string('vehicle_type');              // e.g., "Truck", "Van"
            $table->string('vehicle_plate')->unique();
            $table->string('vehicle_capacity')->nullable(); // e.g., "500kg"
            $table->enum('availability_status', ['available', 'on_route', 'off_duty', 'on_leave'])->default('available');
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_updated_at')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_collections')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};