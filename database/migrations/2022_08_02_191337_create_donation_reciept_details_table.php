<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationRecieptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_reciept_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('donation_receipt_id')->nullable();
            $table->foreign('donation_receipt_id')->references('id')->on('donation_receipts')->onDelete('cascade');
            $table->integer('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->integer('payment_method_detail_id')->nullable();
            $table->foreign('payment_method_detail_id')->references('id')->on('payment_method_details');
            $table->string('payment_method_field_value', 255)->nullable();
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
        Schema::dropIfExists('donation_reciept_details');
    }
}
