<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Carbon;

class PlanController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $planes = \App\Plan::all();

        return view('admin.planes', [
            'mensaje'       => 'Planes Activos',
            'planes'       => $planes
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        /*paso 1 
        * verificar si existen pproductos terminados para se agregados a un nuevo plan
        * Si no existen productos con cantidad_e >= 1 enviar a plan.index con alerta de productos no disponibles
        * Si existen productos con >= 1 en cantidad_e, se envia a la vista de creacion de plan con la coleccion de productos a agregar + join de recetas + join de users
        */

        $productosOk  = \DB::table('produccion')
                        ->where('cantidad_e','>=',1)
                        ->get();
        //dd($productosOk);
        if (count($productosOk) == 0) {
            
            return \Redirect::back()->withErrors('No existe produccion');

        } else {
            //print_r("existen productos");

            foreach ($productosOk as $value) {
                $totalEntrada[] = $value->cantidad_e;
                $totalSalida[]  = $value->cantidad_s;
            }

            $totalEntrada   = array_sum($totalEntrada);
            $totalSalida    = array_sum($totalSalida);

            $totalPlan = $totalEntrada - $totalSalida;


            $produccion = \DB::table('produccion')
                    ->join('recetas','recetas.id','=','produccion.id_r')
                    ->select('produccion.*','recetas.nombre')
                    ->where('produccion.cantidad_s','=',0)
                    ->get();

            return view('admin.plan_nuevo', [
            'mensaje'          => 'Nuevo Plan de Venta',
            'productos'        => $produccion,
            'totalPlan'        => $totalPlan
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //datos de prueba
        $user_id    = \Auth::id();
        $codigo     = "PLAN-" . time();
        $timer      = Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $timerF      = Carbon\Carbon::tomorrow('America/Caracas');
        /*
        * Nueva funcionalidad fecha de publicacion de menu
        * si no se envia una fecha, por defecto el sistema creara la fecha proxima siguiente tomorrow
        * $timerF      = Carbon\Carbon::tomorrow('America/Caracas');
        */
        if(!empty($request->publicar)){
                    
            //validamos que sea una fecha valida y que no sea pasada
            $inputData      = explode("-",  $request->publicar);
            $inputDate      = Carbon\Carbon::create($inputData[2], $inputData[1], $inputData[0], 23, 59, 59);

                $pattern="/^((19|20)?[0-9]{2})[\/|-](0?[1-9]|[1][012])[\/|-](0?[1-9]|[12][0-9]|3[01])$/";

                if(preg_match($pattern, $inputDate->toDateString())){

                    //verificando si $inputDate es mayor o igual al dia de manana
                    $validDate      = $inputDate->gte($timerF); //true / false

                    if($validDate == false){

                        return \Redirect::back()->withErrors('La fecha programada es pasada o esta en un rango inferior a 24 horas');

                    } else {

                         $timerF     = $inputDate->toDateString();

                    }


                } else {

                    return \Redirect::back()->withErrors('El recurso fecha publicar, no es una fecha valida');

                }
        }


        if ($request->clase == "sencillo") {
            
            /* 
            ** convencion de operacion
            ** sumamos las cantidade de servicios y restamos las existentes en produccion
            ** verificando si existen planes con produccion del mismo ID
            ** Si el servicio es igual al cargado [d,a,c]., hacer update al plan correspondiente
            ** si no creamos uno nuevo con menu inactio
            */

            $consultaProducto = \DB::table('produccion')
                                    ->where('id','=',$request->id)
                                    ->get();

            //validamos que la cantidad recibida sea menor o igual a la cantidad existente
            
            if ($request->cantidad > $consultaProducto[0]->cantidad_e) {

                return \Redirect::back()->withErrors('Las cantidades enviadas son superiores a las procesables');

            }
            

            //agregamos la cantidad de produccion asignada

            $produccionAsignada = $request->cantidad + $consultaProducto[0]->cantidad_s;

            //ajustamos la cantidad

            $ajusteProducto = \DB::table('produccion')
                                    ->where('id','=',$request->id)
                                    ->update([
                                        'cantidad_s'=> $produccionAsignada,
                                        'updated_at' => $timer,
                                        ]);

            $consultaPlan =  \DB::table('planes')
                                    ->where('produccion_id','=',$request->id)
                                    ->where('servicio','=',$request->servicios)
                                    ->get();

            if(count($consultaPlan) == 1) {

                // actualizamos el plan existente
                $cantidad = $consultaPlan[0]->cantidad + $request->cantidad;

                $ajustePlan = \DB::table('planes')
                                 ->where('produccion_id','=',$request->id)
                                 ->update([
                                    'cantidad'   => $cantidad,
                                    'updated_at' => $timer,
                                    ]);

                return back();


            } 

            //insertamos nuevo plan y preparamos el siguiente menu 
            $nuevoPlan = \DB::table('planes')
                            ->insertGetId([
                                    'produccion_id' => $request->id,
                                    'cantidad'      => $request->cantidad,
                                    'codigo'        => $codigo,
                                    'user_id'       => $user_id,
                                    'servicio'      => $request->servicios,
                                    'created_at'    => $timer,
                                    'updated_at'    => $timer,
                                ]);
            //creamos nuevo menu por defecto en estado 'no activo' 
            //para se actualizado con un nombre apropiado
            $nuevoPl[] = [$nuevoPlan => $produccionAsignada];
            $nuevoMenu = \DB::table('menus')
                            ->insert([
                                    'nombre'        => $codigo,
                                    'plan'          => json_encode($nuevoPl),
                                    'codigo'        => $codigo,
                                    'activo'        => 'no',
                                    'seccion'       => $request->seccion,
                                    'user_id'       => $user_id,
                                    'publicar'      => $timerF,
                                    'created_at'    => $timer,
                                    'updated_at'    => $timer,
                                ]);
            
            return \Redirect::back()->withErrors('Nueva Planificacion Creada disponible para Menu');

        } else {
             
            /**
             * mas de un producto seran actualizado y/o incertados en la BD
             * paso1
             * verificar si ya existen productos planificados con caracteristicas similares a las enviadas
             * si existen se actualizan las cantidades y se les asigna el nombre al menu 
             **/

            $claves = preg_split("/[\s,]+/", $request->produccion);
            
            foreach ($claves as $key => $value) {
                if (!empty($value)) {
                    $id[] = $value;
                }
                
            }

            //consulta para verificar si existen planes con las mismas caracteriticas en estado activo
            $planVer = \DB::table('planes')
                            ->whereIn('produccion_id',$id)
                            ->where('servicio',$request->servicios)
                            ->get();

            if (count($planVer) == 0) {
                //corremos un bucle para crear el/los plan(es) 
                foreach ($id as $key => $value) {

                    $consultaProducto = \DB::table('produccion')
                                    ->where('id','=',$value)
                                    ->get();

                    //agregamos la cantidad de produccion asignada

                    $produccionAsignada  = ($consultaProducto[0]->cantidad_e - $consultaProducto[0]->cantidad_s);
                    $produccionCierre    = $produccionAsignada + $consultaProducto[0]->cantidad_s;

                    //ajustamos la cantidad

                    $ajusteProducto = \DB::table('produccion')
                                            ->where('id','=',$value)
                                            ->update([
                                                'cantidad_s' => $produccionCierre,
                                                'updated_at' => $timer,
                                                ]);

                    //insertamos nuevo plan y preparamos el siguiente menu 
                    $nuevoPlan = \DB::table('planes')
                                    ->insertGetId([
                                            'produccion_id' => $value,
                                            'cantidad'      => $produccionAsignada,
                                            'codigo'        => $codigo,
                                            'user_id'       => $user_id,
                                            'servicio'      => $request->servicios,
                                            'created_at'    => $timer,
                                            'updated_at'    => $timer,
                                        ]);
                    
                    $nuevoPl[] = [$nuevoPlan => $produccionAsignada]; 

                    

                }
                //creamos nuevo menu por defecto en estado 'activo'
                    $nuevoMenu = \DB::table('menus')
                                    ->insert([
                                            'nombre'        => $request->menu_name,
                                            'plan'          => json_encode($nuevoPl),
                                            'codigo'        => $codigo,
                                            'activo'        => 'si',
                                            'seccion'       => $request->seccion,
                                            'user_id'       => $user_id,
                                            'publicar'      => $timerF,
                                            'created_at'    => $timer,
                                            'updated_at'    => $timer,
                                        ]);
                //regreso a la pagina de produccion
               return \Redirect::back()->withErrors('Nuevo '. $request->menu_name .' Menu Creado!!!');

            } else {
                $claves = preg_split("/[\s,]+/", $request->produccion);
            dd($claves);
                foreach ($claves as $key => $value) {
                    if (!empty($value)) {
                        $id[] = $value;
                    }

                }
                
                //corremos un bucle para crear el/los plan(es) 
                foreach ($id as $key => $value) {

                    $consultaProducto = \DB::table('produccion')
                                    ->where('id','=',$value)
                                    ->get();

                    //agregamos la cantidad de produccion asignada

                    $produccionAsignada  = ($consultaProducto[0]->cantidad_e - $consultaProducto[0]->cantidad_s);
                    $produccionCierre    = $produccionAsignada + $consultaProducto[0]->cantidad_s;

                    //ajustamos la cantidad

                    $ajusteProducto = \DB::table('produccion')
                                            ->where('id','=',$value)
                                            ->update([
                                                'cantidad_s' => $produccionCierre,
                                                'updated_at' => $timer,
                                                ]);

                    //insertamos nuevo plan y preparamos el siguiente menu 
                    $nuevoPlan = \DB::table('planes')
                                    ->insertGetId([
                                            'produccion_id' => $value,
                                            'cantidad'      => $produccionAsignada,
                                            'codigo'        => $codigo,
                                            'user_id'       => $user_id,
                                            'servicio'      => $request->servicios,
                                            'created_at'    => $timer,
                                            'updated_at'    => $timer,
                                        ]);
                    
                    $nuevoPl[] = [$nuevoPlan => $produccionAsignada]; 

                    

                }
                //creamos nuevo menu por defecto en estado 'activo'
                    $nuevoMenu = \DB::table('menus')
                                    ->insert([
                                            'nombre'        => $request->menu_name,
                                            'plan'          => json_encode($nuevoPl),
                                            'codigo'        => $codigo,
                                            'activo'        => 'si',
                                            'seccion'       => $request->seccion,
                                            'user_id'       => $user_id,
                                            'publicar'      => $timerF,
                                            'created_at'    => $timer,
                                            'updated_at'    => $timer,
                                        ]);
                
                return \Redirect::back()->withErrors('Nuevo '. $request->menu_name .' Menu Creado!!!');
                //
            }

        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * $id Id de plan para obtener rangos de fecha acordes con las planificaciones
     * ejecutadas
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * TestCases 
     */
    public function planreporte() {
        //cargamos planes a partir de un rango de fecha viable de planes ejecutados
        //$plan = \DB::table('plan')->where(null)->get();
        //testcase
        $meses = array('enero'=>1, 'febrero'=>2, 'marzo'=>3, 'abril'=>4, 'mayo'=>5, 'junio'=>6, 'julio'=>7, 'agosto'=>8, 'septiembre'=>9, 'octubre'=>10, 'noviembre'=>11, 'diciembre'=>12);
        $year = Carbon\Carbon::now()->year;
        //$month = Carbon\Carbon::now()->month;
        //dd($month);
        $salida = array();
        foreach($meses as $mes=>$month){
            $date = Carbon\Carbon::createFromDate($year,$month);
            $daysPerWeeks = Carbon\Carbon::DAYS_PER_WEEK;
            $numberOfWeeks = floor($date->daysInMonth / $daysPerWeeks);
            $start = [];
            $j=1;
            for ($i=1; $i <= $date->daysInMonth ; $i++) {
                Carbon\Carbon::createFromDate($year,$month,$i); 
                $a = (array)Carbon\Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
                $b = (array)Carbon\Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
                $start['Semana: '.$j]= array_merge($a,$b);

                $i+=7;
                $j++; 
            }
        $salida[$mes] = $start;
        }
        
        return $salida;
        
    }
}
