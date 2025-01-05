<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForiegnColumnsToDonationReceiptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_reciept_details', function (Blueprint $table) {
            $table->integer('donation_payment_method_id')->after('payment_method_id')->nullable();
            $table->foreign('donation_payment_method_id')->references('id')->on('donation_payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_reciept_details', function (Blueprint $table) {
            $table->dropColumn('donation_payment_method_id');
        });
    }
}
