<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNcnformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ncnforms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester_id');
            $table->integer('company_id');
            $table->integer('department_id');
            $table->integer('approver_id');
            $table->integer('notified_id')->nullable();
            $table->integer('non_conformity_types');
            $table->string('notification_number');
            $table->string('recurrence_number');
            $table->datetime('issuance_date');
            $table->datetime('date_request');
            $table->datetime('approved_date')->nullable();
            $table->datetime('disapproved_date')->nullable();
            $table->string('non_conformity_details');
            $table->string('status');
            $table->string('others')->nullable();
            $table->string('remarks')->nullable();
            $table->text('action_taken')->nullable();
            $table->datetime('action_date')->nullable();
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
        Schema::dropIfExists('ncnforms');
    }
}
