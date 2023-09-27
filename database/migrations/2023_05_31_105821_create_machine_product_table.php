<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_product', function (Blueprint $table) {
            $table->unsignedInteger('machine_id');
            $table->unsignedInteger('product_id');
            $table->float('speed'); // Parametro de Velocidad
            $table->timestamps();

            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_product');
    }
}
