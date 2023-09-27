<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('machine_id');
            $table->enum('shift', ['D', 'N'])->nullable();
            $table->enum('warehouse', ['AL', 'CU'])->nullable();
            $table->integer('produced_meters')->nullable();
            $table->integer('production_speed')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->text('no_production_reason')->nullable();
            $table->time('hour')->nullable();
            $table->date('round_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('rounds');
    }
}
