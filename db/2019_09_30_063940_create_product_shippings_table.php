<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_shipping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("product_id")->unsigned();
            $table->integer("shipping_id")->unsigned();
            $table->timestamps();
			$table->foreign('product_id')->references('id')->on('catalog_products');
			$table->foreign('shipping_id')->references('id')->on('catalog_shipping');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_product_shipping');
    }
}
