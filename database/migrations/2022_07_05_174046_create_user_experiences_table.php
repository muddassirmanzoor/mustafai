<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_experiences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('title_english')->nullable();
            $table->string('title_urdu')->nullable();
            $table->string('title_arabic')->nullable();
            $table->string('experience_company_english')->nullable();
            $table->string('experience_company_urdu')->nullable();
            $table->string('experience_company_arabic')->nullable();
            $table->date('experience_start_date')->nullable();
            $table->date('experience_end_date')->nullable();
            $table->string('experience_location_english')->nullable();
            $table->string('experience_location_urdu')->nullable();
            $table->string('experience_location_arabic')->nullable();
            $table->boolean('is_currently_working')->nullable()->default(0);
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
        Schema::dropIfExists('user_experiences');
    }
}
