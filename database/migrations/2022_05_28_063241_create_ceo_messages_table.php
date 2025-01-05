<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCeoMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ceo_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->binary('message_english')->nullable();
            $table->binary('message_urdu')->nullable();
            $table->binary('message_arabic')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0=inactive,1=active')->nullable();
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
        Schema::dropIfExists('ceo_messages');
    }
}
