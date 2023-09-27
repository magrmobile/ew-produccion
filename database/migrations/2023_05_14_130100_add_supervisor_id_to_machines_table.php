<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupervisorIdToMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->unsignedInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn('supervisor_id');
        });
    }
}
