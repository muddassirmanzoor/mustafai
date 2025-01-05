<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPlanApplicationPronotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_plan_application_pronotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('application_id')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1=pronote,2=raseed pronote');
            $table->string('name',255)->nullable();
            $table->string('guardian_name',255)->nullable();
            $table->string('nic',255)->nullable();
            $table->string('address',255)->nullable();
            $table->string('tehcil',255)->nullable();
            $table->string('district',255)->nullable();
            $table->string('amount',255)->nullable();
            $table->string('amount_half',255)->nullable();
            $table->string('service_charges',255)->nullable();
            $table->string('check_number',255)->nullable();
            $table->string('owner',255)->nullable();
            $table->string('bank',255)->nullable();
            $table->string('alabd',255)->nullable();
            $table->integer('check_date')->nullable();
            $table->integer('date')->nullable();
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
        Schema::dropIfExists('business_plan_application_pronotes');
    }
}
