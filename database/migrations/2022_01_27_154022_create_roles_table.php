<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_english', 100)->nullable();
            $table->string('name_urdu', 100)->nullable();
            $table->string('name_arabic', 100)->nullable();
            $table->text('right_ids')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('type')->default(1)->comment('1=admin,2=user');
            $table->softDeletes();
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
        Schema::dropIfExists('roles');
    }
}
