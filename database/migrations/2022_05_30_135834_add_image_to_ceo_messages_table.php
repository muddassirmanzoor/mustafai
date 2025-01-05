<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToCeoMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ceo_messages', function (Blueprint $table) {
            //
            $table->text('image')->after('admin_id')->nullable();
            $table->text('message_title')->after('admin_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceo_messages', function (Blueprint $table) {
            $table->dropColumn('image')->after('admin_id')->nullable();
            $table->dropColumn('message_title')->after('admin_id')->nullable();
        });
    }
}
