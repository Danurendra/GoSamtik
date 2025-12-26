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
            // Menyimpan pilihan ukuran: small, medium, large, custom
            $table->string('waste_size')->default('small')->after('service_type_id');
            // Menyimpan estimasi berat (kg)
            $table->decimal('estimated_weight', 8, 2)->default(1.00)->after('waste_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['waste_size', 'estimated_weight']);
        });
    }
};
