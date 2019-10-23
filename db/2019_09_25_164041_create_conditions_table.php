<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['min-price','max-price','region','role']);
            $table->string('value');
            $table->enum('discount_type',["subtract","add"]);
            $table->integer('discount_value');
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
        Schema::dropIfExists('catalog_conditions');
    }
}
