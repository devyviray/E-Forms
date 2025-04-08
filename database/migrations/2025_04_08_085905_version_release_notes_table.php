<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VersionReleaseNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('version_release_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('version_release_id');  // Use unsignedInteger for foreign key compatibility

            $table->string('type');
            $table->string('description');

            $table->unsignedInteger('user_id');  // Use unsignedInteger for foreign key compatibility
            $table->timestamps();
    
            $table->foreign('version_release_id')->references('id')->on('version_release')->onDelete('cascade');  // Foreign key to version_releases
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // Foreign key to users
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('version_release_notes');
    }
}
