<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDdrformsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ddrforms_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id');
            $table->string('document_title');
            $table->string('control_code');
            $table->string('rev_number');
            $table->string('copy_number');
            $table->string('copy_holder');
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
        Schema::dropIfExists('ddrforms_lists');
    }
}
