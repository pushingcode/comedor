<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    //
    protected $table = 'produccion';

    protected $fillable = [
        'id',
        'id_r',
        'cantidad_e',
        'cantidad_s',
        'codigo',
        'user'
    ];
    
    protected $dates = [
      'created_at',
    ];
    //protected $dateFormat = 'd/m/Y h:i:s a';
}
