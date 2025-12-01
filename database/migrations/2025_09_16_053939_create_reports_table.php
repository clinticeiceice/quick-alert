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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
        $table->enum('level', ['1', '2', '3']);
        $table->text('description')->nullable();
        $table->enum('status', ['pending', 'approved', 'accepted', 'declined'])->default('pending');
        $table->enum('designated_to', ['rescue', 'pnp', 'bfp']);
        $table->timestamp('reported_at')->useCurrent();
        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
