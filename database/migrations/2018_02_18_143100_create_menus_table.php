<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     * LONGTEXT
     * $table->LONGTEXT('plan');
     * $table->json('plan');
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('plan');
            $table->string('codigo');
            $table->enum('activo', ['si', 'no']);
            $table->enum('seccion', ['comedor', 'delivery', 'vip']);
            $table->integer('user_id')->unsigned();
            $table->dateTime('publicar');
            $table->timestamps();
        });
        Schema::table('menus', function($table) {
            //$table->foreign('plan_id')->references('id')->on('planes');
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
        Schema::dropIfExists('menus');
    }
}
