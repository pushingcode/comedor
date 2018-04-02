<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produccion_id')->unsigned();
            $table->integer('cantidad');
            $table->string('codigo');
            $table->integer('user_id')->unsigned();
            $table->enum('servicio', ['desayuno', 'almuerzo', 'cena']);
            $table->timestamps();
        });
        Schema::table('planes', function($table) {
            $table->foreign('produccion_id')->references('id')->on('produccion');
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
        Schema::dropIfExists('planes');
    }
}
