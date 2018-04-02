<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //

    protected $table = 'planes';

    protected $fillable = [
    	'id',
    	'produccion_id',
    	'cantidad',
    	'codigo',
    	'user_id',
    	'servicio'
    ];

    protected $dates = [
      'created_at',
      'updated_at'
    ];
    //protected $dateFormat = 'd/m/Y h:i:s a';
}
