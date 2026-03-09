<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_user', function (Blueprint $table) {
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->unique(['process_id', 'user_id']);
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_user');
    }
}
