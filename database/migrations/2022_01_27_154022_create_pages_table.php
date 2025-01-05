<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id');
            $table->string('title')->nullable();
            $table->string('url')->nullable()->unique();
            $table->text('short_description')->nullable();
            $table->binary('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->tinyInteger('in_header')->comment('1=yes,0=no')->default(0);
            $table->tinyInteger('in_footer')->comment('1=yes,0=no')->default(0);
            $table->tinyInteger('is_feature')->comment('1=yes,0=no')->default(0);
            $table->tinyInteger('status')->comment('1=yes,0=no')->default(0);
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
        Schema::dropIfExists('pages');
    }
}
