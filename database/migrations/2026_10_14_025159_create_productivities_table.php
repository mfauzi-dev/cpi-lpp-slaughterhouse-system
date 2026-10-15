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
        Schema::create('productivities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->unsignedInteger('worker_indirect')->default(0);
            $table->unsignedInteger('worker_direct')->default(0);
            $table->unsignedInteger('worker_total')->default(0);
            $table->decimal('total_weight', 12, 2)->default(0);
            $table->decimal('total_time', 10, 2)->default(0);
            $table->decimal('productivity_indirect', 12, 2)->default(0);
            $table->decimal('productivity_direct', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productivities');
    }
};
