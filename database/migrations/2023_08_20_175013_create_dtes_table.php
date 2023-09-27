<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('numeroControl', 31);
            $table->string('codigoGeneracion', 36);
            $table->json('json_dte');
            $table->unsignedInteger('created_by');
            $table->boolean('signed')->default(false);
            $table->timestamp('signed_date')->nullable();
            $table->text('sign')->nullable();
            $table->boolean('received')->default(false);
            $table->timestamp('received_date')->nullable();
            $table->text('stamp')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtes');
    }
}
