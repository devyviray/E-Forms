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
            $table->bigIncrements('id');  // Laravel 5.5 uses increments() for primary key
            $table->bigInteger('version_release_id');
            $table->bigInteger('user_id');
            $table->longText('feedback');
            $table->timestamps();
            $table->softDeletes();
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
