<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
         //   $table->integer('company_id')->unsigned();
         //   $table->integer('role_id')->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('department_id');
            $table->string('position');
            // $table->string('api_token', 60)->unique();
            $table->rememberToken();
            $table->timestamps();

        //    $table->foreign('company_id')->references('id')->on('companies');
        //    $table->foreign('department_id')->references('id')->on('departments');
         //   $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
