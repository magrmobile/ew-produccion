<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterLocationEnumOnMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE machines
            MODIFY COLUMN location
            ENUM('Santo Tomas', 'Santa Elena', 'San Luis Talpa')
            NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            ALTER TABLE machines
            MODIFY COLUMN location
            ENUM('Santo Tomas', 'Santa Elena')
            NOT NULL
        ");
    }
}
