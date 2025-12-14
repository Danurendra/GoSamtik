<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema:: create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('total_stops')->default(0);
            $table->integer('completed_stops')->default(0);
            $table->decimal('total_distance_km', 8, 2)->nullable();
            $table->json('optimized_sequence')->nullable();  // Array of collection IDs in order
            $table->timestamps();

            // One route per driver per day
            $table->unique(['driver_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};