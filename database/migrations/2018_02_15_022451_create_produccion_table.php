<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produccion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_r')->unsigned();
            $table->integer('cantidad_e');
            $table->integer('cantidad_s')->default(0);
            $table->string('codigo');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            
        });
        
        Schema::table('produccion', function($table) {
            $table->foreign('id_r')->references('id')->on('recetas');
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
        Schema::dropIfExists('produccion');
    }
}
