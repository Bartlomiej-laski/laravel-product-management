<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLinkedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_linked', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("product_id")->unsigned();
            $table->integer("linked_id")->unsigned();
            $table->enum("type",["course","blog","product","bundle"]);
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
        Schema::dropIfExists('catalog_product_linked');
    }
}
