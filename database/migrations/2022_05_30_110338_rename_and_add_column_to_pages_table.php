<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAndAddColumnToPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_urdu')->after('title')->nullable();
            $table->string('title_arabic')->after('title_urdu')->nullable();
            $table->text('short_description_urdu')->after('short_description')->nullable();
            $table->text('short_description_arabic')->after('short_description_urdu')->nullable();
            $table->binary('description_urdu')->after('description')->nullable();
            $table->binary('description_arabic')->after('description_urdu')->nullable();

            $table->renameColumn('title', 'title_english');
            $table->renameColumn('short_description', 'short_description_english');
            $table->renameColumn('description', 'description_english');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('title_english','title');
            $table->renameColumn('short_description_englsih','short_description');
            $table->renameColumn('description_english','description');
        });
    }
}
