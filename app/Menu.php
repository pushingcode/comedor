<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //

    protected $table = 'menus';

    protected $fillable = [
    	'id',
    	'nombre',
    	'plan_id',
    	'activo',
    	'seccion',
    	'user_id',
      'publicar'
    ];

    protected $dates = [
      'publicar',
      'created_at',
      'updated_at'
    ];
    //protected $dateFormat = 'd/m/Y h:i:s a';
}
