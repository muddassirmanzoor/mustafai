<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationRecieptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_receipts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('donation_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->float('amount')->nullable();
            $table->string('receipt', 255)->nullable();
            $table->tinyInteger('status')->comment('0=not verified,1=verified')->nullable();
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
        Schema::dropIfExists('donation_receipts');
    }
}
