<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->unique()->index();

            $table->string('name');
            $table->string('email');

            $table->string('svn', 255)->nullable();
            $table->string('username', 1024)->nullable();
            $table->string('password', 2048)->nullable();

            $table->bigInteger('hook_id')->unsigned()->nullable();

            $table->bigInteger('organisation_id')->unsigned()->nullable();
            $table->foreign('organisation_id')
                ->references('id')->on('organisations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repositories');
    }
}
