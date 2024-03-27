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
        Schema::table('gates', function (Blueprint $table) {
            $table->index('name'); // Adding an index to the 'name' column
        });
    }

    public function down()
    {
        Schema::table('gates', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
