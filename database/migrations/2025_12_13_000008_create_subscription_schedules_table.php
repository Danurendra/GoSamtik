<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('preferred_time_start')->default('08:00:00');
            $table->time('preferred_time_end')->default('12:00:00');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Prevent duplicate days for same subscription
            $table->unique(['subscription_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_schedules');
    }
};