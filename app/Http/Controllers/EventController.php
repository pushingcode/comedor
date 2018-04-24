<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon;
use Calendar;
class EventController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {
      # code...
      $user       = \Auth::user();
      $timer      = Carbon\Carbon::now()->format('Y-m-d H:i:s');
      $events     = [];
      $color      = '#b93d23';
      $uri        = '/#';
      $data       = \DB::table('menus')->get();
      if(count($data) == 0){
        return \Redirect::back()->withErrors('No exiten datos para el calendario');
      }

      foreach ($data as $key => $value) {
        $rango = explode(" ", $value->publicar);
        $fromDate = $value->publicar;
        $toDate = $rango[0] . ' 23:59:59';
        $theDate = Carbon\Carbon::parse($toDate);

        //verificando si es fecha psada

        $validDate = $theDate->gte($timer); //true / false

        if ($validDate == true) {
          $color  = '#4d79ff';
          $uri    = '#';
          //verificamos si el menu esta ordenado
          $orden = \DB::table('ordenes')
                      ->where([['user_id','=',$user->id],['menu_id','=',$value->id]])
                      ->get();
          if(count($orden) == 0){
            $color  = '#2ab923';
            $uri    = 'menu/'.$value->id;
          }
        }

        $evento = $value->nombre." (".substr($value->seccion,0,1).")";


                $events[] = Calendar::event(
                    $evento,
                    true,
                    $fromDate,
                    $toDate,
                    null,
                    // Add color and link on event
	                [
	                    'color'  => $color,
	                    'url'    => $uri,
	                ]
                );
            }

        $calendar = Calendar::addEvents($events);
        return view('calendario', compact('calendar'));
    }
}
