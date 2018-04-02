<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvetariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invetarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_p')->unsigned();
            $table->integer('cantidad')->default(0);
        });
        
        Schema::table('invetarios', function($table) {
            $table->foreign('id_p')->references('id')->on('materia_prima');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invetarios');
    }
}
