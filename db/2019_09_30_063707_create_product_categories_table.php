<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("product_id")->unsigned();
            $table->integer("category_id")->unsigned();
            $table->enum("type",["blog","course","bundle","product"]);
            $table->timestamps();
			//$table->foreign('product_id')->references('id')->on('catalog_products');
			$table->foreign('category_id')->references('id')->on('categories');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_product_categories');
    }
}
