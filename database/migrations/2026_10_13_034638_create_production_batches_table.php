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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->unsignedTinyInteger('shift')->nullable();
            $table->string('conveyor')->nullable();
            $table->unsignedInteger('absen_worker')->nullable();
            $table->date('tanggal_transfer')->nullable();
            $table->date('tanggal_proses')->nullable();
            $table->string('cost_center_name')->nullable();
            $table->string('no_po')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
