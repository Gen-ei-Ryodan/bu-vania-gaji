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
        Schema::table('bibits', function (Blueprint $table) {
            $table->dropColumn(['estimasi_panen', 'realisasi_panen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bibits', function (Blueprint $table) {
            $table->date('estimasi_panen')->after('tanggal_masuk');
            $table->date('realisasi_panen')->nullable()->after('estimasi_panen');
        });
    }
};
