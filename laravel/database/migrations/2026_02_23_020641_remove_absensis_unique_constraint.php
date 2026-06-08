<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Remove the unique constraint that prevents multiple absensi per employee per date
     * This enables multiple half day records for the same employee on the same date
     */
    public function up(): void
    {
        // Drop the unique constraint that blocks multiple absensi
        // We need to handle foreign key constraints carefully
        
        // First, get the foreign key constraints
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'absensis' 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        // Drop foreign key constraints temporarily
        foreach ($foreignKeys as $foreignKey) {
            DB::statement("ALTER TABLE absensis DROP FOREIGN KEY {$foreignKey->CONSTRAINT_NAME}");
        }
        
        // Drop the unique constraint
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique(['karyawan_id', 'tanggal']);
        });
        
        // Recreate foreign key constraints
        Schema::table('absensis', function (Blueprint $table) {
            $table->foreign('karyawan_id')->references('id')->on('karyawans')->onDelete('cascade');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('restrict');
            $table->foreign('lokasi_id')->references('id')->on('lokasis')->onDelete('restrict');
            $table->foreign('kandang_id')->references('id')->on('kandangs')->onDelete('restrict');
            $table->foreign('bibit_id')->references('id')->on('bibits')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the original unique constraint
        
        // Get the foreign key constraints
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'absensis' 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        // Drop foreign key constraints temporarily
        foreach ($foreignKeys as $foreignKey) {
            DB::statement("ALTER TABLE absensis DROP FOREIGN KEY {$foreignKey->CONSTRAINT_NAME}");
        }
        
        // Add back the unique constraint
        Schema::table('absensis', function (Blueprint $table) {
            $table->unique(['karyawan_id', 'tanggal']);
        });
        
        // Recreate foreign key constraints
        Schema::table('absensis', function (Blueprint $table) {
            $table->foreign('karyawan_id')->references('id')->on('karyawans')->onDelete('cascade');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('restrict');
            $table->foreign('lokasi_id')->references('id')->on('lokasis')->onDelete('restrict');
            $table->foreign('kandang_id')->references('id')->on('kandangs')->onDelete('restrict');
            $table->foreign('bibit_id')->references('id')->on('bibits')->onDelete('set null');
        });
    }
};