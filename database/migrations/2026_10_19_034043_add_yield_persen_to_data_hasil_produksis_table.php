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
        Schema::table('data_hasil_produksis', function (Blueprint $table) {
            $table->decimal('yield_persen', 8, 2)->default(0)->after('total_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_hasil_produksis', function (Blueprint $table) {
            $table->dropColumn('yield_persen');
        });
    }
};
