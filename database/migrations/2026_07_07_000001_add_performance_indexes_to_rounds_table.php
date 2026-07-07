<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPerformanceIndexesToRoundsTable extends Migration
{
    public function up()
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->index(['machine_id', 'round_date', 'hour'], 'rounds_machine_date_hour_idx');
            $table->index(['user_id', 'round_date'], 'rounds_user_date_idx');
            $table->index('round_date', 'rounds_date_idx');
        });
    }

    public function down()
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropIndex('rounds_machine_date_hour_idx');
            $table->dropIndex('rounds_user_date_idx');
            $table->dropIndex('rounds_date_idx');
        });
    }
}
