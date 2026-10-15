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
        Schema::create('metal_detectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->cascadeOnDelete();

            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();

            $table->string('no_program')->nullable();

            $table->enum('fe_depan', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();
            $table->enum('fe_tengah', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();
            $table->enum('fe_belakang', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();

            $table->enum('non_fe_depan', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();
            $table->enum('non_fe_tengah', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();
            $table->enum('non_fe_belakang', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();

            $table->enum('sus_316_depan', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();
            $table->enum('sus_316_tengah', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();
            $table->enum('sus_316_belakang', ['Terdeteksi', 'Tidak terdeteksi'])->nullable();

            $table->string('in')->nullable();
            $table->string('out')->nullable();

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metal_detectors');
    }
};
