<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDonationRecieptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_receipts', function (Blueprint $table) {
            $table->integer('payment_method_id')->after('user_id')->nullable();
            $table->string('email')->after('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_receipts', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
        });
    }
}
