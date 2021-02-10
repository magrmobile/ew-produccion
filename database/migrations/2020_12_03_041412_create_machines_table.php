<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');

            $table->string('machine_name')->unique();
            //$table->string('process'); // Trefilado, Cableado, Fraccionado
            $table->string('warehouse'); // AL(Aluminio), CU(Cobre)

            // fk - device
            $table->unsignedInteger('device_id')->nullable();
            $table->foreign('device_id')->references('id')->on('devices');

            // fk - process
            $table->unsignedInteger('process_id')->nullable();
            $table->foreign('process_id')->references('id')->on('processes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machines');
    }
}
