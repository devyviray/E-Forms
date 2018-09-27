<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcirformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ccirforms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester_id');
            $table->integer('company_id');
            $table->string('complainant');
            $table->string('commodity');
            $table->string('product_control_number');
            $table->datetime('date_request');
            $table->datetime('cancel_date')->nullable();
            $table->datetime('delivery_date');
            $table->datetime('returned_date')->nullable();
            $table->integer('nature_of_complaint');
            $table->string('others')->nullable();
            $table->string('other_details');
            $table->integer('affected_quantity');
            $table->string('quality_of_sample');
            $table->string('car_number')->nullable();
            $table->integer('verifier_id')->nullable();
            $table->datetime('verified_date')->nullable();
            $table->string('status');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('ccirs');
    }
}
