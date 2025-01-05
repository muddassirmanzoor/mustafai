<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name', 255)->nullable();
            $table->string('user_name_english', 255)->nullable();
            $table->string('user_name_urdu', 255)->nullable();
            $table->string('user_name_arabic', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->unsignedBigInteger('occupation_id')->nullable();
//            $table->foreign('occupation_id')->references('id')->on('occupations')->onDelete('cascade');
            $table->string('phone_number', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('original_password', 255)->nullable();

            $table->integer('country_code_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('tehsil_id')->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('union_council_id')->nullable();

            $table->string('banner')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('tagline_english')->nullable();
            $table->string('tagline_urdu')->nullable();
            $table->string('tagline_arabic')->nullable();
            $table->string('cnic')->nullable();
            $table->string('address_english')->nullable();
            $table->string('address_urdu')->nullable();
            $table->string('address_arabic')->nullable();
            $table->string('blood_group_english')->nullable();
            $table->string('blood_group_urdu')->nullable();
            $table->string('blood_group_arabic')->nullable();
            $table->longText('about_english')->nullable();
            $table->longText('about_urdu')->nullable();
            $table->longText('about_arabic')->nullable();
            $table->integer('login_role_id')->nullable();
            $table->integer('subscription_fallback_role_id')->nullable();
            $table->rememberToken();
            $table->tinyInteger('status')->default(0)->comment('0=in-active,1=active');
            $table->tinyInteger('is_public')->default(0)->comment('1=public,0=not public');
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
        Schema::dropIfExists('users');
    }
}
