<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title_english', 255)->nullable();
            $table->string('title_urdu', 255)->nullable();
            $table->string('title_arabic', 255)->nullable();
            $table->binary('description_english', 255)->nullable();
            $table->binary('description_urdu', 255)->nullable();
            $table->binary('description_arabic', 255)->nullable();
            $table->float('price')->nullable();
            $table->string('file')->nullable();
            $table->tinyInteger('donation_type')->comment('1=public,2=private')->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->nullable();
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
        Schema::dropIfExists('donations');
    }
}
