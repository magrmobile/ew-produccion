<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->increments('id');

            // fk machine
            $table->unsignedInteger('machine_id');
            $table->foreign('machine_id')->references('id')->on('machines');

            // fk operator
            $table->unsignedInteger('operator_id');
            $table->foreign('operator_id')->references('id')->on('users');

            // fk product
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            // fk color
            $table->unsignedInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colors');

            // fk code
            $table->unsignedInteger('code_id');
            $table->foreign('code_id')->references('id')->on('codes');

            $table->integer('meters')->nullable();
            $table->string('comment')->nullable();

            // Fecha y Hora de Inicio de Stop
            $table->date('stop_date_start');
            $table->time('stop_time_start');

            // Fecha y Hora de Fin de Stop
            $table->date('stop_date_end')->nullable();
            $table->time('stop_time_end')->nullable();

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
        Schema::dropIfExists('stops');
    }
}
