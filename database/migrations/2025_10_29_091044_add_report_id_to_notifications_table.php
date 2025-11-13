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
    // Schema::table('notifications', function (Blueprint $table) {
    //     $table->unsignedBigInteger('report_id')->nullable(); // Adjust as needed (e.g., foreign key)
    // });
}
public function down()
{
    // Schema::table('notifications', function (Blueprint $table) {
    //     $table->dropColumn('report_id');
    // });
}
};
