<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->index()->nullable();
            $table->integer('user_id')->index()->nullable();
            $table->integer('share_id')->index()->nullable();
//            $table->integer('share_post_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('post_type')->index()->nullable()
                ->comment('1=simple post, 2=job post, 3=announcment post, 4=product post, 5= blood post');
            $table->text('title_english')->nullable();
            $table->text('title_urdu')->nullable();
            $table->text('title_arabic')->nullable();

            $table->string('job_seeker_name')->nullable();
            $table->string('job_seeker_or_hire_email')->nullable();
            $table->string('job_seeker_or_hire_phone')->nullable();
            $table->string('job_seeker_currently_working')->nullable();
            $table->string('job_seeker_or_hire_job_type')->nullable();
            $table->string('hiring_company_name')->nullable();

            $table->string('occupation')->nullable();
            $table->string('experience')->nullable();
            $table->text('skills')->nullable();
            $table->string('resume')->nullable();

            $table->binary('description_english')->nullable();
            $table->binary('description_urdu')->nullable();
            $table->binary('description_arabic')->nullable();
            $table->string('price')->nullable();

            $table->string('city')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('hospital')->nullable();
            $table->string('address')->nullable();

            $table->tinyInteger('job_type')->nullable()->index()->comment('1=hiring, 2= seeking');

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
        Schema::dropIfExists('posts');
    }
}
