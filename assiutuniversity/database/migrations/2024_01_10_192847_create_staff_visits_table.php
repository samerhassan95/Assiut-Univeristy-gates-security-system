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
        Schema::create('staff_visits', function (Blueprint $table) {
            $table->id();
            $table->string('nationalID');
            $table->string('name');
            $table->string('position');
            $table->dateTimeTz('visit_datetime',0);
            $table->string('gate_name')->nullable();
            $table->enum('enter_outer', ['enter', 'outer'])->nullable();
            $table->timestamps();

            // Composite primary key for uniqueness
            $table->unique(['nationalID', 'visit_datetime']);

            // Foreign key constraint
            $table->foreign('nationalID')->references('nationalID')->on('staff')->onDelete('cascade');
            $table->foreign('gate_name')->references('name')->on('gates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_visits');
    }
};
