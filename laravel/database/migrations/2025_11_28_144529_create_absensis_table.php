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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatans')->onDelete('restrict');
            $table->foreignId('lokasi_id')->constrained('lokasis')->onDelete('restrict');
            $table->foreignId('kandang_id')->constrained('kandangs')->onDelete('restrict');
            $table->foreignId('bibit_id')->nullable()->constrained('bibits')->onDelete('set null');
            $table->enum('tipe_absen', ['full', 'half']);
            $table->date('tanggal');
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
