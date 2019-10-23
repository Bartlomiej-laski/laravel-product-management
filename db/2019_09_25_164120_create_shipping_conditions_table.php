<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_shipping_conditions', function (Blueprint $table) {
            $table->increments('id');
			$table->integer("shipping_id")->unsigned();
			$table->integer("condition_id")->unsigned();
			$table->timestamps();
			$table->foreign('shipping_id')->references('id')->on('catalog_shipping');
			$table->foreign('condition_id')->references('id')->on('catalog_conditions');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_shipping_conditions');
    }
}
