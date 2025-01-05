<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPlanApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_plan_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('plan_id')->nullable();
            $table->integer('applicant_id')->nullable();
            $table->integer('selected_date')->nullable();
            $table->string('form_serial_number',255)->nullable();
            $table->integer('form_date')->nullable();
            $table->string('form_contact_number',255)->nullable();
            $table->string('form_nic_number',255)->nullable();
            $table->string('form_full_name',255)->nullable();
            $table->string('form_guardian_name',255)->nullable();
            $table->string('form_business_coessentiality',255)->nullable();
            $table->string('form_plan_reason',255)->nullable();
            $table->string('form_temp_address',255)->nullable();
            $table->string('form_permanent_address',255)->nullable();
            $table->string('form_image',255)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=unapproved,1=approved,2=declined');
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
        Schema::dropIfExists('business_plan_applications');
    }
}
