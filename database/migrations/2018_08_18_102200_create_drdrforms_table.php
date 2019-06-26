<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrdrformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drdrforms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_type');
            $table->string('document_title');
            $table->string('reason_request');
            $table->string('rev_number')->nullable();
            $table->dateTime('date_request');
            $table->dateTime('effective_date')->nullable();
            $table->integer('company_id');
            $table->integer('requester_id');
            $table->integer('reviewer_id');
            $table->datetime('reviewed_date')->nullable();
            $table->integer('approver_id')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('disapproved_date')->nullable();
            $table->string('drdr_no')->nullable();
            $table->string('document_code')->nullable();
            $table->integer('distributed_id')->nullable();
            $table->datetime('distributed_date')->nullable();
            $table->integer('status');
            $table->string('consider_documents')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('drdrforms');
    }
}
