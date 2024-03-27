<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToStudentVisitsTable extends Migration
{
    public function up()
    {
        Schema::table('student_visits', function (Blueprint $table) {
            $table->foreign('status')->references('status')->on('students')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('student_visits', function (Blueprint $table) {
            $table->dropForeign(['status']);
        });
    }
}
