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
        Schema::table('productivities', function (Blueprint $table) {
            $table->foreignId('bahan_baku_id')
                ->nullable()
                ->after('material_id')
                ->constrained('bahan_bakus')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productivities', function (Blueprint $table) {
            $table->dropForeign(['bahan_baku_id']);
            $table->dropColumn('bahan_baku_id');
        });
    }
};
