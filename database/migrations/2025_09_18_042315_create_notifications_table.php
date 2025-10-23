<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('notifications', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id');
    $table->string('message');
    $table->string('role');
    $table->boolean('is_read')->default(false);
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
