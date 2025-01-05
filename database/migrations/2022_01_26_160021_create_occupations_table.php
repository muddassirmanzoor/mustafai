<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('title_english')->nullable();
            $table->string('title_urdu')->nullable();
            $table->string('title_arabic')->nullable();
            $table->binary('content_english')->nullable();
            $table->binary('content_urdu')->nullable();
            $table->binary('content_arabic')->nullable();
            $table->string('in_header')->default(0)->comment('0=inactive,1=active');
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
        Schema::dropIfExists('occupations');
    }
}
