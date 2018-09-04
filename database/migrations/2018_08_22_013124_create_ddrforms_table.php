<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDdrformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ddrforms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('department_id');
            $table->integer('reason_of_distribution');
            $table->string('others')->nullable();
            // $table->string('document_title');
            // $table->string('control_code');
            // $table->string('rev_number');
            // $table->string('copy_number');
            // $table->string('copy_holder');
            $table->datetime('date_request');
            $table->datetime('date_needed')->nullable();
            $table->datetime('effective_date')->nullable();
            $table->integer('requester_id');
            $table->integer('reviewer_id');
            $table->datetime('reviewed_date')->nullable();
            $table->integer('approver_id')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->datetime('dispproved_date')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('ddrforms');
    }
}
