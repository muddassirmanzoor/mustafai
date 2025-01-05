<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_education', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('institute_english')->nullable();
            $table->string('institute_urdu')->nullable();
            $table->string('institute_arabic')->nullable();
            $table->string('degree_name_english')->nullable();
            $table->string('degree_name_urdu')->nullable();
            $table->string('degree_name_arabic')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('user_education');
    }
}
