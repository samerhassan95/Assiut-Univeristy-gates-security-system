<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyFromStudentVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_visits', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_visits', function (Blueprint $table) {
            // You can re-add the foreign key constraint if needed in the down method
            // Example: $table->foreign('status')->references('status')->on('students')->onDelete('cascade');
        });
    }
}
