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
            $table->unsignedInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            // fk color
            $table->unsignedInteger('color_id')->nullable();
            $table->foreign('color_id')->references('id')->on('colors');

            // fk code
            $table->unsignedInteger('code_id');
            $table->foreign('code_id')->references('id')->on('codes');

            // fk conversion
            $table->unsignedInteger('conversion_id')->nullable();
            $table->foreign('conversion_id')->references('id')->on('conversions');

            $table->integer('quantity')->nullable();

            $table->float('meters')->nullable();

            $table->string('comment')->nullable();

            // Fecha y Hora de Inicio de Stop
            $table->dateTime('stop_datetime_start')->nullable();

            // Fecha y Hora de Fin de Stop
            $table->dateTime('stop_datetime_end')->nullable();

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
