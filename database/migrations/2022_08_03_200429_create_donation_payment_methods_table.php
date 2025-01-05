<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_payment_methods', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->string('account_title')->nullable();
            $table->tinyInteger('status')->comment('0=inactive,1=active')->default('1')->nullable();
            $table->unique(["payment_method_id", "account_title"], 'donation_payment_methods_unique');
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
        Schema::dropIfExists('donation_payment_methods');
    }
}
