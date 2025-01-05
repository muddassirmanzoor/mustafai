<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationPaymentMethodDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_payment_method_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('donation_pay_method_id')->nullable();
            $table->foreign('donation_pay_method_id')->references('id')->on('donation_payment_methods')->onDelete('cascade');
            $table->integer('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->integer('payment_method_detail_id')->nullable();
            $table->foreign('payment_method_detail_id')->references('id')->on('payment_method_details');
            $table->string('payment_method_field_value', 255)->nullable();
            // $table->unique(["payment_method_id", "payment_method_detail_id", "payment_method_field_value"], 'donation_payment_method_detail_unique');
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
        Schema::dropIfExists('donation_payment_method_details');
    }
}
