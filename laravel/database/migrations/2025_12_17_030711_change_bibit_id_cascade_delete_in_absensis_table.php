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
        Schema::table('absensis', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['bibit_id']);
            
            // Recreate foreign key with cascade delete
            $table->foreign('bibit_id')
                ->references('id')
                ->on('bibits')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Drop cascade foreign key
            $table->dropForeign(['bibit_id']);
            
            // Restore original foreign key with set null
            $table->foreign('bibit_id')
                ->references('id')
                ->on('bibits')
                ->onDelete('set null');
        });
    }
};
