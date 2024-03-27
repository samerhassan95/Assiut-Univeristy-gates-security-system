<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToStatusInStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down()
    {
        // This is optional, you can decide whether to drop the index during rollback
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
}
