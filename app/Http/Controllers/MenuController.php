<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

class MenuController extends Controller
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
        //$checkMenus = \DB::table('menus')
        /*
        * Carga de todos los menus en estado activo
        */
       $menus = \App\Menu::paginate(15);
//        $ordenesE = \App\Ordenes::where('entregado','si');
//        $ordenesP = \App\Ordenes::where('entregado','no');
       
        
        if (count($menus) == 0) {

            return \Redirect::back()->withErrors('No existen menus activos');

        }
        
        foreach ($menus as $menu){
            
//            $controlTime = \Carbon\Carbon::now();
//            $carbonObject = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $menu->updated_at, 'America/Caracas');
//            $addTime = $carbonObject->addHours(4);
//            $comparaFecha = $controlTime->gt($addTime);
//            
//            $estado = ($comparaFecha == true) ? 'Caducado':'Activo';
            
            $ordenesP[$menu->id] = \DB::table('ordenes')
                            ->select(DB::raw("count(ordenes.id) AS count"))
                            ->where([['ordenes.entregado','=','no'],['ordenes.menu_id','=',$menu->id],['ordenes.deleted_at','=',null]])
                            ->groupBy('ordenes.id')
                            ->get();
            
            $ordenesE[$menu->id] = \DB::table('ordenes')
                            ->select(DB::raw("count(ordenes.id) AS count"))
                            ->where([['ordenes.entregado','=','si'],['ordenes.menu_id','=',$menu->id],['ordenes.deleted_at','=',null]])
                            ->groupBy('ordenes.id')
                            ->get();
        }
        
        
        return view('admin.menu',[
            'mensaje'   => 'Administracion de Menus', 
            'menus'     => $menus,
            'ordenesP' => $ordenesP,
            'ordenesE' => $ordenesE,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //cargamos todos los menus que estan inactivos y les ajustamos el nombre
        $planes = [];
        
        $menusInactivos = \DB::table('menus')
                    ->join('planes','menus.plan','=','planes.codigo')
                    ->select(
                            'menus.*',
                            'planes.produccion_id AS idPlanes',
                            'planes.codigo AS codigoPlanes',
                            'planes.servicio AS servicioPlan')
                    ->where('activo','no')
                    ->get();
        
        //cargando datos para envios a la vista
            foreach ($menusInactivos as $menuConsulta){
                $planes[] = \DB::table('recetas')
                        ->join('produccion','recetas.id', '=', 'produccion.id_r')
                        ->select(
                                'recetas.*',
                                'produccion.id AS idProduccion',
                                'produccion.id_r AS recProduccion',
                                'produccion.cantidad_e AS entradaProduccion',
                                'produccion.cantidad_s AS salidaProduccion',
                                'produccion.codigo AS codigoProduccion')
                        ->where('produccion.id','=',$menuConsulta->idPlanes)
                        ->get();
            }
            //dd($planes);
        return view('admin.menus',[
            'mensaje' => 'Activacion de Menus', 
            'menus' => $menusInactivos,
            'planes'=>$planes
                ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //info menu
        $cantidad = 0;
        $sumArrayP = array();
        $sumArrayC = array();
        $tipoP     = array();
        $tipoC     = array();

        $menu = \DB::table('menus')
                ->join('planes','menus.codigo','=','planes.codigo')
                ->select(
                        'menus.*',
                        'planes.produccion_id AS idPlanes',
                        'planes.codigo AS codigoPlanes',
                        'planes.servicio AS servicioPlan')
                ->where([
                    ['menus.id','=', $id],
                    ['menus.activo','=','si']
                    ])
                ->get();

        if (count($menu) == 0) {
            return \Redirect::back()->withErrors('No existe el menu solicitado');
        }
        foreach ($menu as $key => $value) {
            $decode = json_decode($value->plan, true);


            foreach ($decode as $planes => $plan) {

                foreach ($plan as $key => $value) {
                     $pl[] = $key;
                }
            }
        }

        $produccion = \DB::table('planes')
                        ->whereIn('id', $pl)
                        ->get();

        
        foreach ($produccion as $key => $value) {
           $pr[] = $value->produccion_id;
        }

        $producido = \DB::table('produccion')
                        ->join('recetas','recetas.id','=','produccion.id_r')
                        ->select('produccion.*',
                            'recetas.id AS receta_id',
                            'recetas.nombre AS recetaNombre',
                            'recetas.tipo AS recetaTipo')
                        ->whereIn('produccion.id', $pr)
                        ->get();
        //ordenes bajo el menu $id
        $ordenes = \DB::table('ordenes')
                    ->where('menu_id','=',$id)
                    ->get();

        if (count($ordenes) == 0) {
            foreach($producido as $value){

                if ($value->recetaTipo == 'principal') {
                    $cargaPlatoP = \DB::table('recetas')
                            ->where('id',$value->id_r)
                            ->get();

                    $payLoadDescrTakeP[$value->id."-".$value->cantidad_s."-".$cantidad] = [$cargaPlatoP[0]->nombre => $cargaPlatoP[0]->receta];
                }
                
                if ($value->recetaTipo == 'contorno') {
                    $cargaPlatoC = \DB::table('recetas')
                            ->where('id',$value->id_r)
                            ->get();
                    $payLoadDescrTakeC[$value->id] = [$cargaPlatoC[0]->nombre => $cantidad];
                }
            }

        } else {
            $decode = array();
            foreach($ordenes as $value){
                $decode[] = json_decode($value->codigo, true);
                //dd($decode);
                foreach ($decode as $value) {
                    //dd($value);
                    foreach ($value as $key1 => $value1) {


                        foreach($decode as $receta){

                        
                        //consultando planificacionesconc on producciones
                        foreach ($receta as $clave => $valor) {

                            if($clave == 'principal'){
                                $priPro = \DB::table('produccion')
                                        ->join('planes','planes.produccion_id','=','produccion.id')
                                        ->select('produccion.id_r AS receta',
                                                'produccion.cantidad_s AS Scantidad')
                                        ->where('planes.id','=',$valor)
                                        ->get();

                                foreach ($priPro as $platoPrin) {
                                    $cargaPlatoP = \DB::table('recetas')
                                                ->where('id','=',$platoPrin->receta)
                                                ->get();
//dd($value);
                                    $payLoadDescrTakeP[$value[0]['principal']."-".$platoPrin->Scantidad] = [$cargaPlatoP[0]->nombre => $cantidad];
                                }
                                
                            }

                            if($clave == 'contorno1'){
                                $priC1 = \DB::table('produccion')
                                        ->join('planes','planes.produccion_id','=','produccion.id')
                                        ->select('produccion.id_r AS receta')
                                        ->where('planes.id','=',$valor)
                                        ->get();

                                foreach ($priC1 as $platoC1) {
                                    $cargaPlatoC1 = \DB::table('recetas')
                                                ->where('id','=',$platoC1->receta)
                                                ->get();

                                    $payLoadDescrTakeC1[$value[0]['contorno1']] = [$cargaPlatoC1[0]->nombre => $cantidad];
                                }
                                
                            }

                            if($clave == 'contorno2'){
                                $priC2 = \DB::table('produccion')
                                        ->join('planes','planes.produccion_id','=','produccion.id')
                                        ->select('produccion.id_r AS receta')
                                        ->where('planes.id','=',$valor)
                                        ->get();

                                foreach ($priC2 as $platoC2) {
                                    $cargaPlatoC2 = \DB::table('recetas')
                                                ->where('id','=',$platoC2->receta)
                                                ->get();

                                    $payLoadDescrTakeC2[$value[0]['contorno2']] = [$cargaPlatoC2[0]->nombre => $cantidad];
                                }
                                
                            }
                        }
                        
                    }


                    /*----------------------------------------------------
                        if ($key1 == 'principal') {
                            $cargaPlatoP = \DB::table('recetas')
                                    ->where('id',$value1)
                                    ->get();

                            $payLoadDescrTakeP[$value->id."-".$value->cantidad_s] = [$cargaPlatoP[0]->nombre => $cargaPlatoP[0]->receta];
                        }
                        
                        if ($key1 == 'contorno1') {
                            $cargaPlatoC = \DB::table('recetas')
                                    ->where('id',$value1)
                                    ->get();
                            $payLoadDescrTakeC[$value->id] = [$cargaPlatoC[0]->nombre => $cantidad];
                        }

                    ----------------------------------------------------*/





                    }
                }
                
            }
        }

        


       // dd($payLoadDescrTakeP);


        foreach ($payLoadDescrTakeP as $key => $value) {
            $key = explode("-",$key);
            foreach ($value as $plato => $receta) {
                if( ! array_key_exists($plato, $sumArrayP)) $sumArrayP[$plato] = 0;

                    $sumArrayP[$plato]+=$cantidad;
                    $tipoP[$plato."-".$key[0]."-".$key[1]]=$receta;
            }
        }


        foreach ($payLoadDescrTakeC1 as $key => $value) {
            foreach ($value as $plato => $cantidad) {
                if( ! array_key_exists($plato, $sumArrayC)) $sumArrayC[$plato] = 0;

                    $sumArrayC[$plato]+=$cantidad;
                    $tipoC[$plato]=$key;
            }
        }



        $planes = \DB::table('recetas')
                ->join('produccion','recetas.id', '=', 'produccion.id_r')
                ->select(
                        'recetas.*',
                        'produccion.id AS idProduccion',
                        'produccion.id_r AS recProduccion',
                        'produccion.cantidad_e AS entradaProduccion',
                        'produccion.cantidad_s AS salidaProduccion',
                        'produccion.codigo AS codigoProduccion')
                ->where('produccion.id','=',$menu[0]->idPlanes)
                ->get();

        //modo super superusuario 
       $user = \Auth::user();
        if($user->hasRole('superadmin')) {
            return \Redirect::back()->withErrors('Esta version no cuenta con la funcionalidad menu avanzado');
        }

        $cliente = \DB::table('clientes')
                ->where([['clientes.user_id','=', $user->id],['clientes.activo','=','si']])
                ->get();

        if(count($cliente) == 0) {
            //return \Redirect::back()->withErrors('El cliente no existe  o se encuentra desactivado');
        }

        $empresa = \DB::table('empresa')
                ->where([['empresa.id','=',$cliente[0]->empresa_id],['empresa.activo','=','si']])
                ->get();

        if(count($empresa) == 0) {
            return \Redirect::back()->withErrors('La empresa no existe  o se encuentra inactiva');
        }

dd($sumArrayC);

        return  view('menu',[
            'mensaje'   => $menu[0]->nombre,
            'clientes'  => $cliente,
            'empresas'  => $empresa,
            'menus'     => $menu,
            'ordenes'   => $ordenes,
            'planes'    => $planes,
            'payLoadP'  => $sumArrayP,
            'payLoadC'  => $sumArrayC,
            'payloadpP' => $tipoP,
            'payLoadcC' => $tipoC
        ]);
        
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
        $ejecutar = false;
        switch ($request->orden){
        case 'cerrar':
            $query =['activo' => 'no'];
            $mensaje = 'Menu desactivado';
            $ejecutar = true;
            break;
        default;
            $mensaje = 'Recurso enviado no existe';
            break;
        }
        if ($ejecutar == true){
            \DB::table('menus')
            ->where('id', $id)
            ->update($query);
        }
        
        return \Redirect::back()->withErrors($mensaje);
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
    
    public function activate(Request $request, $id)
    {
        //
        $user = \Auth::user();
        if(!$user->can('activar menu')) {
            return \Redirect::back()->withErrors('No posee roles para ejecutar esa accion');
        }
        
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return \Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
        \DB::table('menus')
            ->where('id', $id)
            ->update([
                        'nombre' => $request->nombre,
                        'activo' => 'si'
                    ]);
        
        return \Redirect::back()->withErrors('Menu Activado Correctamente');
        
        
        
        
    }
}
