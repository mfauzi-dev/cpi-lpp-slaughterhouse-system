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
        Schema::create('data_hasil_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->decimal('domestik_kg', 12, 2)->default(0);
            $table->decimal('further_kg', 12, 2)->default(0);
            $table->decimal('frozen_kg', 12, 2)->default(0);
            $table->decimal('rm', 12, 2)->default(0);
            $table->unsignedInteger('total_bag')->default(0);
            $table->decimal('total_kg', 12, 2)->default(0);
            $table->decimal('loss_kg', 12, 2)->default(0);
            $table->decimal('loss_persen', 8, 2)->default(0);
            $table->decimal('pickup_kg', 12, 2)->default(0);
            $table->decimal('pickup_persen', 8, 2)->default(0);
            $table->time('pengecekan_suhu_jam')->nullable();
            $table->decimal('pengecekan_suhu_suhu', 6, 2)->nullable();
            $table->string('packaging_nama_kemasan')->nullable();
            $table->decimal('packaging_jumlah_kg', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_hasil_produksis');
    }
};
