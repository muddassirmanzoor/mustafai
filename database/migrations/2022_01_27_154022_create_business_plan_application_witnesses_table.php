<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPlanApplicationWitnessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_plan_application_witnesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('application_id')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1=family,2=outsider');
            $table->string('name',255)->nullable();
            $table->string('guardian_name',255)->nullable();
            $table->string('nic',255)->nullable();
            $table->string('relation',255)->nullable();
            $table->string('business',255)->nullable();
            $table->string('contact_number',255)->nullable();
            $table->string('nic_front',255)->nullable();
            $table->string('nic_back',255)->nullable();
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
        Schema::dropIfExists('business_plan_application_witnesses');
    }
}
