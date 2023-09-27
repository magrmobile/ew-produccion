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

            // fk - device
            $table->unsignedInteger('device_id')->nullable();
            $table->foreign('device_id')->references('id')->on('devices')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // fk - process
            $table->unsignedInteger('process_id')->nullable();
            $table->foreign('process_id')->references('id')->on('processes')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->string('machine_name');
            $table->string('warehouse'); // AL(Aluminio), CU(Cobre)
            $table->string('location'); // Santo Tomas, Santa Elena

            $table->unique(['machine_name', 'location']);

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
