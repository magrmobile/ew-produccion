<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvalidateToDtes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtes', function (Blueprint $table) {
            $table->boolean('invalidate')->default(false);
            $table->timestamp('invalidate_date')->nullable();
            $table->unsignedInteger('invalidated_by')->nullable();
            $table->text('invalidate_stamp')->nullable();

            $table->foreign('invalidated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtes', function (Blueprint $table) {
            //
        });
    }
}
