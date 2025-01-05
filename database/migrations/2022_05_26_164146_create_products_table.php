<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('id', true);
            $table->integer('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name_english')->nullable();
            $table->string('name_urdu')->nullable();
            $table->string('name_arabic')->nullable();
            $table->string('url_key')->nullable();
            $table->string('colors')->nullable();
            $table->string('sizes')->nullable();
            $table->binary('description_english')->nullable();
            $table->binary('description_urdu')->nullable();
            $table->binary('description_arabic')->nullable();

            $table->integer('price')->nullable();
            $table->tinyInteger('featured')->default(0)->comment('0=no,1=yes');
            $table->tinyInteger('is_shipment_charges_apply')->default(0)->comment('0=no,1=yes');
            $table->tinyInteger('new')->default(0)->comment('0=no,1=yes');
            $table->tinyInteger('status')->default(0)->comment('0=inactive,1=active');
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
