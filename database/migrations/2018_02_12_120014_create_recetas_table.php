<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *  LONGTEXT
     * $table->json('receta');
     * $table->LONGTEXT('receta');
     * @return void
     */
    public function up()
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->enum('tipo', ['principal', 'contorno', 'bebida']);
            $table->LONGTEXT('receta');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('recetas', function ($table) {
            $table->softDeletes();
        });

        Schema::table('recetas', function($table) {
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
        Schema::dropIfExists('recetas');
    }
}
