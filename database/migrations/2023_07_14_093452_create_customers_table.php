<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 14);
            $table->string('nrc', 14);
            $table->string('name', 250);
            $table->string('codActividad', 6);
            $table->string('nombreComercial', 150);
            $table->string('tipoEstablecimiento', 2);
            $table->string('departamento', 2)->nullable();
            $table->string('municipio', 2)->nullable();
            $table->string('complemento', 200)->nullable();
            $table->unsignedInteger('codPais');
            $table->string('codDomiciliado', 1);
            $table->string('codigoMH', 4)->nullable();
            $table->string('puntoVentaMH', 4)->nullable();
            $table->string('bienTitulo', 2)->nullable();
            $table->unsignedInteger('tipoPersona')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 100)->nullable();

            $table->timestamps();

            /*$table->foreign('codActividad')->references('codActividad')->on('cat019')->onDelete('cascade');
            $table->foreign('tipoEstablecimiento')->references('id')->on('cat009')->onDelete('cascade');
            $table->foreign('departamento')->references('id')->on('cat012')->onDelete('cascade');
            $table->foreign('municipio')->references('id')->on('cat013')->onDelete('cascade');
            $table->foreign('codPais')->references('id')->on('cat020')->onDelete('cascade');
            $table->foreign('codDomiciliado')->references('id')->on('cat032')->onDelete('cascade');
            $table->foreign('bienTitulo')->references('id')->on('cat025')->onDelete('cascade');
            $table->foreign('tipoPersona')->references('id')->on('cat029')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
