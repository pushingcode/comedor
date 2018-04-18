<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Calendar;
class EventController extends Controller
{
    //
    public function index()
    {
      # code...
      $events = [];
      $data = \DB::table('menus')
      ->get();
      if(count($data) == 0){
        return \Redirect::back()->withErrors('No exiten datos para el calendario');
      }

      foreach ($data as $key => $value) {
        $rango = explode(" ", $value->publicar);
        $fromDate = $value->publicar;
        $toDate = $rango[0] . ' 23:59:59';
                $events[] = Calendar::event(
                    $value->nombre,
                    true,
                    $fromDate,
                    $toDate,
                    null,
                    // Add color and link on event
	                [
	                    'color' => '#f05050',
	                    'url' => '#',
	                ]
                );
            }

        $calendar = Calendar::addEvents($events);
        return view('calendario', compact('calendar'));
    }
}
