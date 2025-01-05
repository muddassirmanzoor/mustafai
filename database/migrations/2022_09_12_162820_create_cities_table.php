<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->integer('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->string('name_english', 255)->nullable();
            $table->string('name_urdu', 255)->nullable();
            $table->string('name_arabic', 255)->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->nullable();
            $table->unique(["province_id", "name_english"], 'cities_unique_english');
            $table->unique(["province_id", "name_urdu"], 'cities_unique_urdu');
            $table->unique(["province_id", "name_arabic"], 'cities_unique_arabic');
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
        Schema::dropIfExists('cities');
    }
}
