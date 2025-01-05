<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->text('image')->nullable();
            $table->text('name_english')->nullable();
            $table->text('name_urdu')->nullable();
            $table->text('name_arabic')->nullable();
            $table->text('short_description_english')->nullable();
            $table->text('short_description_urdu')->nullable();
            $table->text('short_description_arabic')->nullable();
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
        Schema::dropIfExists('employee_sections');
    }
}
