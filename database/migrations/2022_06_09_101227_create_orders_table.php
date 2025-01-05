<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index()->nullable();
            $table->integer('billing_city_id')->index()->nullable();
            $table->integer('billing_country_id')->index()->nullable();
            $table->integer('shipping_city_id')->index()->nullable();
            $table->integer('shipping_country_id')->index()->nullable();
            $table->ipAddress('ip')->index()->nullable();
            $table->string('receipt')->index()->nullable();
            $table->string('billing_user_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone_number')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('shipping_user_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone_number')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipment_amount')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('grand_total')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=pending,2=shipped,3=completed');
            $table->tinyInteger('is_billing_address_is_shipping')->default(0)->comment('0=no,1=yes');
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
        Schema::dropIfExists('orders');
    }
}
