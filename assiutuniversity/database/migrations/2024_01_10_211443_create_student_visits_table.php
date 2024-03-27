<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('student_visits', function (Blueprint $table) {
            $table->id();
            $table->string('nationalID');
            $table->string('name');
            $table->string('faculty');
            $table->dateTimeTz('visit_datetime',0);
            $table->string('status')->nullable();
            $table->timestamps();
            $table->string('gate_name')->nullable();
            $table->enum('enter_outer', ['enter', 'outer'])->nullable();
            // Composite primary key for uniqueness
            $table->unique(['nationalID', 'visit_datetime']);

            // Foreign key constraint if required
            $table->foreign('nationalID')->references('nationalID')->on('students')->onDelete('cascade');
            $table->foreign('gate_name')->references('name')->on('gates');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_visits');
    }
}

