<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     * $table->json('codigo');
     * $table->LONGTEXT('codigo');
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->increments('id');
            $table->LONGTEXT('codigo');
            $table->integer('user_id')->unsigned();
            $table->integer('menu_id')->unsigned();
            $table->enum('entregado', ['si', 'no']);
            $table->timestamps();
        });

        Schema::table('ordenes', function ($table) {
            $table->softDeletes();
        });

        Schema::table('ordenes', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes');
    }
}
