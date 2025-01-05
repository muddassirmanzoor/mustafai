<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLibraryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('library_types', function (Blueprint $table) {
            $table->binary('content_english')->nullable();
            $table->binary('content_urdu')->nullable();
            $table->binary('content_arabic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('library_types', function (Blueprint $table) {
            $table->dropColumn('content_english');
            $table->dropColumn('content_urdu');
            $table->dropColumn('content_arabic');
        });
    }
}
