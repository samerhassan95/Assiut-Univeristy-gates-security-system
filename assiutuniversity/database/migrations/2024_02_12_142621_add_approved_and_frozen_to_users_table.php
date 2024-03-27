<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedAndFrozenToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'approved')) {
                $table->boolean('approved')->default(false);
            }
            if (!Schema::hasColumn('users', 'frozen')) {
                $table->boolean('frozen')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approved');
            $table->dropColumn('frozen');
        });
    }
}
