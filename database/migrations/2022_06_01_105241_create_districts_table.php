<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->integer('id',true);
            $table->integer('division_id')->nullable();
            $table->string('name_english',255)->nullable();
            $table->string('name_urdu',255)->nullable();
            $table->string('name_arabic',255)->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->nullable();
            $table->unique(["division_id", "name_english"], 'districts_unique_english');
            $table->unique(["division_id", "name_urdu"], 'districts_unique_urdu');
            $table->unique(["division_id", "name_arabic"], 'districts_unique_arabic');
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
        Schema::dropIfExists('districts');
    }
}
