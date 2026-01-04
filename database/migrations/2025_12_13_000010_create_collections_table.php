<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema:: create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('collection_type', ['one_time', 'recurring'])->default('one_time');
            $table->date('scheduled_date');
            $table->time('scheduled_time_start');
            $table->time('scheduled_time_end');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'missed'])->default('pending');
            $table->text('service_address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('completion_photo')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('driver_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
