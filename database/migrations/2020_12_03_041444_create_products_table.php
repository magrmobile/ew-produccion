<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');

            $table->string('product_name');
            $table->string('metal_type'); // AL(Aluminio), CU(Cobre)
            $table->string('stock'); // PP, PT
            
            $table->unique(['product_name', 'metal_type', 'stock']);

            // fk family
            $table->unsignedInteger('family_id')->nullable();
            $table->foreign('family_id')->references('id')->on('families');

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
        Schema::dropIfExists('products');
    }
}
