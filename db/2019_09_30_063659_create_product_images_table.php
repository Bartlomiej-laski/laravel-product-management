<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("product_id")->unsigned();
            $table->string('image_url');
            $table->boolean('is_main');
            $table->timestamps();
			$table->foreign('product_id')->references('id')->on('catalog_products');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_product_images');
    }
}
