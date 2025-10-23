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
        // Add 'controlled' as a valid status
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'approved', 'accepted', 'controlled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to original enum values
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'approved', 'accepted') DEFAULT 'pending'");
    }
};
