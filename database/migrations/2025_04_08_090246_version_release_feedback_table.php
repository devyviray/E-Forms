<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VersionReleaseFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('version_release_feedback', function (Blueprint $table) {
            $table->increments('id');  // Laravel 5.5 uses increments() for primary key
            $table->unsignedInteger('user_id');  // Use unsignedInteger for foreign key compatibility
            $table->unsignedInteger('version_release_id');  // Use unsignedInteger for foreign key compatibility
            $table->text('feedback');
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // Foreign key to users
            $table->foreign('version_release_id')->references('id')->on('version_release')->onDelete('cascade');  // Foreign key to version_releases
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('version_release_feedback');
    }
}
