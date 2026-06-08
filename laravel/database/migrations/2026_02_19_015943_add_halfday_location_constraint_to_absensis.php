<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration documents the change in business logic for half day attendance.
     * The application now allows multiple half day records per employee per date,
     * but still prevents duplicate full day records.
     * 
     * The constraint validation is handled at the application layer in:
     * - App\Http\Requests\StoreAbsensiRequest
     * - App\Http\Requests\UpdateAbsensiRequest  
     * - App\Http\Controllers\AbsensiController
     */
    public function up(): void
    {
        // This is a documentation migration - no database schema changes
        // The business logic change is implemented in the application layer
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is purely for documentation and has no effect when rolled back
    }
};