<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('country_id')->nullable();
            $table->string('name_english', 255)->nullable();
            $table->string('name_urdu', 255)->nullable();
            $table->string('name_arabic', 255)->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->nullable();
            $table->unique(["country_id", "name_english"], 'provinces_unique_english');
            $table->unique(["country_id", "name_urdu"], 'provinces_unique_urdu');
            $table->unique(["country_id", "name_arabic"], 'provinces_unique_arabic');
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
        Schema::dropIfExists('provinces');
    }
}
