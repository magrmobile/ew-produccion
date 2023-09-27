<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            // fk - supervisor
            $table->unsignedInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // fk - process
            $table->unsignedInteger('process_id')->nullable();
            $table->foreign('process_id')->references('id')->on('processes')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('username', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('active_user', ['enabled', 'disabled'])->nullable()->default('enabled');
            
            $table->string('role'); // 'admin', 'operator', 'supervisor', 'guest'

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
