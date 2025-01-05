<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSectionLibraryAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_section_library_albums', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('library_types')->onDelete('cascade');
            $table->integer('employee_section_id')->nullable();
            $table->foreign('employee_section_id')->references('id')->on('employee_sections')->onDelete('cascade');
            $table->integer('library_album_id')->nullable();
            $table->foreign('library_album_id')->references('id')->on('library_albums')->onDelete('cascade');
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
        Schema::dropIfExists('employee_section_library_albums');
    }
}
