<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bibits', function (Blueprint $table) {
            $table->dropForeign(['kandang_id']);
            $table->dropUnique(['kandang_id']);
            $table->index('kandang_id');
            $table->foreign('kandang_id')->references('id')->on('kandangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('bibits', function (Blueprint $table) {
            $table->dropForeign(['kandang_id']);
            $table->dropIndex(['kandang_id']);
            $table->unique('kandang_id');
            $table->foreign('kandang_id')->references('id')->on('kandangs')->onDelete('cascade');
        });
    }
};
