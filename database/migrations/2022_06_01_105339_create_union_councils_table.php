<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnionCouncilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('union_councils', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tehsil_id')->nullable();
            $table->integer('zone_id')->nullable();
            $table->string('name_english', 255)->nullable();
            $table->string('name_urdu', 255)->nullable();
            $table->string('name_arabic', 255)->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->nullable();
            $table->unique(["tehsil_id", "name_english"], 'union_councils_unique_english');
            $table->unique(["tehsil_id", "name_urdu"], 'union_councils_unique_urdu');
            $table->unique(["tehsil_id", "name_arabic"], 'union_councils_unique_arabic');
            $table->unique(["zone_id", "name_english"], 'union_councils_unique_english_tehsil');
            $table->unique(["zone_id", "name_urdu"], 'union_councils_unique_urdu_teshil');
            $table->unique(["zone_id", "name_arabic"], 'union_councils_unique_arabic_tehsil');
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
        Schema::dropIfExists('union_councils');
    }
}
