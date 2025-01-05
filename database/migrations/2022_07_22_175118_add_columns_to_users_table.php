<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('resume', 255)->after('profile_image')->nullable();
            $table->text('skills_english')->after('resume')->nullable();
            $table->text('skills_urdu')->after('skills_english')->nullable();
            $table->text('skills_arabic')->after('skills_urdu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('resume');
            $table->dropColumn('skills');
        });
    }
}
