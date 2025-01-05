<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_by')->nullable();
            $table->string('name_english',255)->nullable();
            $table->string('name_urdu',255)->nullable();
            $table->string('name_arabic',255)->nullable();
            $table->tinyInteger('type')->default(0)->comment('1=monthly,2=weekly,3=daily');
            $table->integer('total_invoices')->nullable();
            $table->integer('invoice_amount')->nullable();
            $table->integer('total_users')->nullable();
            $table->binary('description_english')->nullable();
            $table->binary('description_urdu')->nullable();
            $table->binary('description_arabic')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=unread,1=read,2=unset,3=edited');
            $table->integer('start_date')->nullable();
            $table->integer('end_date')->nullable();
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
        Schema::dropIfExists('business_plans');
    }
}
