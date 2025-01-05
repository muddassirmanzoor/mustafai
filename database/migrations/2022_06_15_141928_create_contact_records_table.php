<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_records', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->binary('message', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('subject', 255)->nullable();
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
        Schema::dropIfExists('contact_records');
    }
}
