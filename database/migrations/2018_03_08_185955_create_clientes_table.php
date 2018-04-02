<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('empresa_id')->unsigned();
            $table->enum('cargo', ['admin', 'usuario']);
            $table->enum('activo', ['si', 'no']);
            $table->timestamps();
        });

        Schema::table('clientes', function ($table) {
            $table->softDeletes();
        });

        Schema::table('clientes', function($table) {
            $table->foreign('empresa_id')->references('id')->on('empresa');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
