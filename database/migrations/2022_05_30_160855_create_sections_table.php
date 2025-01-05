<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name_english')->nullable();
            $table->string('name_urdu')->nullable();
            $table->string('name_arabic')->nullable();
            $table->binary('content_english')->nullable();
            $table->binary('content_urdu')->nullable();
            $table->binary('content_arabic')->nullable();
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
        Schema::dropIfExists('sections');
    }
}
