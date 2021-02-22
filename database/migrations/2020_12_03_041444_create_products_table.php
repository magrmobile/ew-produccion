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

            // fk family
            $table->unsignedInteger('family_id')->nullable();
            $table->foreign('family_id')->references('id')->on('families')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // fk process
            $table->unsignedInteger('process_id')->nullable();
            $table->foreign('process_id')->references('id')->on('processes')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->string('product_name');
            $table->string('metal_type'); // AL(Aluminio), CU(Cobre)
            $table->string('stock')->nullable(); // PP, PT
            
            //$table->unique(['product_name', 'metal_type', 'stock']);

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
