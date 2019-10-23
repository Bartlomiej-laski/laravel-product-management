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
        Schema::create('catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title");
            $table->string("slug");
            $table->text("description");
            $table->integer("price");
            $table->integer("sale_price")->nullable();
			$table->integer("discount")->nullable();
            $table->integer("total_price");
            $table->integer("units")->nullable();
            $table->date("published_on");
            $table->enum("status",["published","draft"]);
            $table->string("visibility");
			$table->boolean("has_linked");
			$table->boolean("is_online");
			$table->boolean("is_unlimited");
			$table->boolean("trending");
			$table->boolean("is_draft");
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
        Schema::dropIfExists('catalog_products');
    }
}
