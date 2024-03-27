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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nationalID')->unique();
            $table->string('level');
            $table->string('status');
            $table->string('image')->nullable();
            $table->string('faculty'); // Foreign key reference to faculties table
            $table->foreign('faculty')->references('name')->on('faculties')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('students');
    }
    
};
