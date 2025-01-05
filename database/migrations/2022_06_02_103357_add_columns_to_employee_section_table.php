<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEmployeeSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_sections', function (Blueprint $table) {
            $table->string('designation_english', 255)->after('name_arabic')->nullable();
            $table->string('designation_urdu', 255)->after('designation_english')->nullable();
            $table->string('designation_arabic', 255)->after('designation_urdu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_sections', function (Blueprint $table) {
            $table->dropColumn('designation_english');
            $table->dropColumn('designation_urdu');
            $table->dropColumn('designation_arabic');
        });
    }
}
