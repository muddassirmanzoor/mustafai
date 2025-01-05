<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTehsilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tehsils', function (Blueprint $table) {
            $table->integer('id',true);
            $table->integer('district_id')->nullable();
            $table->string('name_english',255)->nullable();
            $table->string('name_urdu',255)->nullable();
            $table->string('name_arabic',255)->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->nullable();
            $table->unique(["district_id", "name_english"], 'tehsils_unique_english');
            $table->unique(["district_id", "name_urdu"], 'tehsils_unique_urdu');
            $table->unique(["district_id", "name_arabic"], 'tehsils_unique_arabic');
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
        Schema::dropIfExists('tehsils');
    }
}
