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
        Schema::create('rekapitulasi_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->string('mesin')->nullable();
            $table->time('waktu_start')->nullable();
            $table->time('waktu_stop')->nullable();
            $table->decimal('operation_time', 8, 2)->default(0);
            $table->decimal('speed_kapasitas', 12, 2)->default(0);
            $table->decimal('aktual', 12, 2)->default(0);
            $table->decimal('persen_pencapaian', 8, 2)->default(0);
            $table->decimal('output_kg', 12, 2)->default(0);
            $table->decimal('downtime', 8, 2)->default(0);
            $table->text('penyebab_downtime')->nullable();
            $table->text('tindakan_perbaikan')->nullable();
            $table->string('pic_operator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekapitulasi_produksis');
    }
};
