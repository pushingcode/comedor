<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('empresa_id')->unsigned();
            $table->text('comentario');
            $table->enum('activo', ['si', 'no']);
            $table->timestamps();
        });

        Schema::table('comentarios', function ($table) {
            $table->softDeletes();
        });

        Schema::table('comentarios', function($table) {
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
        Schema::dropIfExists('comentarios');
    }
}
