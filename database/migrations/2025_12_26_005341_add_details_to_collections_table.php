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
        Schema::table('collections', function (Blueprint $table) {
            $table->string('waste_size')->default('small')->after('service_type_id'); // small, medium, large


            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            // Memisahkan tanggal dan jam agar lebih fleksibel
            // Jika sebelumnya hanya 'collection_date', kita ubah jadi datetime atau tambah time
            $table->time('time_slot_start')->nullable();
            $table->time('time_slot_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema:: dropIfExists('collections');
    }
};
