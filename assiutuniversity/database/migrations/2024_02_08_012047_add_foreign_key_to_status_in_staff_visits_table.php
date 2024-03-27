<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToStatusInStaffVisitsTable extends Migration
{
    public function up()
    {
        Schema::table('staff_visits', function (Blueprint $table) {
            $table->foreign('status')->references('status')->on('staff')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('staff_visits', function (Blueprint $table) {
            $table->dropForeign(['status']);
        });
    }
}
