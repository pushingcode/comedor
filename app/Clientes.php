<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    //
    use SoftDeletes;
    protected $table = 'clientes';

    
    protected $dates = [
      'created_at',
      'deleted_at'
    ];
    //protected $dateFormat = 'd/m/Y h:i:s a';
}
