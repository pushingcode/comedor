<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ordenes extends Model
{
    //
    use SoftDeletes;
    protected $table = 'ordenes';

    
    protected $dates = [
      'created_at',
      'deleted_at'
    ];
    //protected $dateFormat = 'd/m/Y h:i:s a';
}
