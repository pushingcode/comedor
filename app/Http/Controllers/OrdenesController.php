<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Carbon;

class OrdenesController extends Controller
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
        // compprobamos que es cliente y que tiene empresa asignada
        $id = \Auth::id();
        $cliente = \DB::table('clientes')
                ->where('user_id', $id)
                ->get();
        $ordenes = [];
        $planes = [];
        $fromDate = date('Y-m-d' . ' 00:00:00', time()); 
        $toDate = date('Y-m-d' . ' 23:59:59', time());
        if(count($cliente) == 0) {
            //el cliente solo es usuario se carga el form de asignacion para empresas
            $empresa="";
            $menu = "";
        } else {
            //el susuario es cliente se carga info de empresa
            $empresa = \DB::table('empresa')
                    ->where('id',$cliente[0]->empresa_id)
                    ->get();
            
            $menu = \DB::table('menus')
                    ->join('planes','menus.codigo','=','planes.codigo')
                    ->select(
                            'menus.*',
                            'planes.produccion_id AS idPlanes',
                            'planes.codigo AS codigoPlanes',
                            'planes.servicio AS servicioPlan')
                    ->where('menus.activo','=','si')
                    ->whereBetween('menus.created_at', [$fromDate, $toDate])
                    ->get();
            //cargando datos para envios a la vista
            //dd($menu);
            foreach ($menu as $menuConsulta){
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
            
            $ordenes = \DB::table('ordenes')
                    ->join('menus','ordenes.menu_id','=','menus.id')
                    ->select(
                            'ordenes.*',
                            'menus.id AS idMenu',
                            'menus.nombre AS nombreMenu',
                            'menus.seccion AS seccionMenu',
                            'menus.created_at AS fechaMenu')
                    ->where([['ordenes.user_id','=',$id],['deleted_at','=',null]])
                    ->get();
        }
        
        return  view('oferta',[
            'clientes'  => $cliente, 
            'empresas'  => $empresa, 
            'menus'     => $menu,
            'ordenes'   => $ordenes,
            'planes'    => $planes
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
        //dd($request->all());
        //verificando si el usuario puede crear menu
        $user = \Auth::user();
//        if(!$user->hasPermissionTo('crear pedido')) {
//            return \Redirect::back()->withErrors('No puedes, generar pedidos');
//        }
        
        //verificamos si el usuario esta activo como cliente
        $clientes  = \DB::table('clientes')
                ->join('empresa','clientes.empresa_id','=','empresa.id')
                ->select('empresa.activo AS empresaActiva',
                        'clientes.activo AS clienteActivo')
                ->where([
                        ['clientes.user_id', '=', $user->id], 
                        ['clientes.activo','=','si']
                        ])
                ->get();
        //dd($clientes);
        if(count($clientes) == 0) {
            return \Redirect::back()->withErrors('No eres cliente');
        }
        
        if($clientes[0]->empresaActiva == 'no') {
            return \Redirect::back()->withErrors('Su empresa no esta activa');
        }
        
        if($clientes[0]->clienteActivo == 'no') {
            return \Redirect::back()->withErrors('No estas activo como cliente');
        }
        //todo bien
        //creamos el codigo
        //timer
        //dd($request->all());
        //[{"principal":$request->principal,"controno":$request->contorno}]
        //$codigo = $request->principal.'+'.$request->contorno;
        $codigo[] = ["principal"=>$request->principal,"contorno"=>$request->contorno];
        
        //dd(json_encode($codigo));
        $id = \Auth::id();
        $ordenes = new \App\Ordenes;

            $ordenes->codigo    = json_encode($codigo);
            $ordenes->user_id   = $id;
            $ordenes->menu_id   = $request->menu;
            $ordenes->entregado = 'no';

        $ordenes->save();
        
        return redirect()->action('OrdenesController@index');
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
        //dependiendo el tipo de orden realizaremos cambios especificos a la orden
        //desde el cambio de entrega hasta modificacion del mismo
        switch ($request->orden) {
            case "cerrar":
                //esta orden cierra el pedido y lo da como entregado
                \DB::table('ordenes')
                ->where('id','=', $id)
                ->update(['entregado' => 'si']);
                
                $mensaje = 'Orden entregada';
                break;
            case 1:
                $mensaje = 'No existe el recurso solicirado. Error de interpretacion ';
                break;
            case 2:
                $mensaje = 'No existe el recurso solicirado. Error de interpretacion ';
                break;
            default :
                
                break;
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
        $user = \Auth::user();

        if (!$user->can('eliminar pedido')) {
            return \Redirect::back()->withErrors('Este usuario no posee roles para eliminar pedido');
        }
        Log::info('El usuario ' . $user->name . " utilizo el metodo SoftDelete sobre la orden ID: " .$id);
        $orden = \App\Ordenes::find($id);
        $orden->delete();
        return \Redirect::back()->withErrors('Orden eliminada');
    }
    
    /**
     * carga el form de entrega de orden.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function entrega($id)
    {
        //
        //carga de ordenes en estado no entregados pertenecientes al menu $id
        $payload = array();
        $orden = \DB::table('ordenes')
                ->join('users','ordenes.user_id','=','users.id')
                ->join('clientes','ordenes.user_id','=','clientes.user_id')
                ->join('empresa','clientes.empresa_id','=','empresa.id')
                ->select('users.id AS userID',
                        'users.name AS userName',
                        'users.email AS userMail',
                        'clientes.activo AS clienteActivo',
                        'empresa.nombre AS empresasNombre',
                        'empresa.activo AS empresasActivo',
                        'ordenes.*')
                ->where([['menu_id','=',$id],['entregado','=','no']])
                ->get();
        
        foreach($orden as $value){
            $decode[] = json_decode($value->codigo, true);
            foreach($decode as $receta){
                //dd($receta[0]['principal']);
                $cargaPlatoP = \DB::table('recetas')
                        ->where('id',$receta[0]['principal'])
                        ->get();
                $cargaPlatoC = \DB::table('recetas')
                        ->where('id',$receta[0]['contorno'])
                        ->get();
                $payload[$value->id] = "Empresa: ".$value->empresasNombre."-Usuario: ".$value->userName."-Principal: ".$cargaPlatoP[0]->nombre."-Contorno: ".$cargaPlatoC[0]->nombre;
            }
                
        }
        
        return view('admin.despacho',['mensaje'=>'Gestion de Ordenes','ordenes' => $orden,'payloads' => $payload]);
    }
    
    /**
     * carga el form seleccionar la empresa a generar reporte.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function creporte() 
    {
        $selsectEmpresa = \App\Empresa::all();
        $idJS = array();
        foreach($selsectEmpresa as $value){
            $idJS[$value->id] = $value->nombre;
            //para cada empresa se setea el ambito de reporte a partir de su
            //creacion como marcador base para enviar la fechas minimas y maximas 
            //de semana reportable
//            $year = Carbon\Carbon::parse($value->created_at)->year;
//            $month = Carbon\Carbon::parse($value->created_at)->month;
            $year = Carbon\Carbon::now()->year;
            $month = Carbon\Carbon::now()->month;
            $date = Carbon\Carbon::createFromDate($year,$month);
            $daysPerWeeks = Carbon\Carbon::DAYS_PER_WEEK;
            $numberOfWeeks = floor($date->daysInMonth / $daysPerWeeks);
            $start = [];
//            $end = [];
//            $ord = [];
            $j=1;
            for ($i=1; $i <= $date->daysInMonth ; $i++) {
                Carbon\Carbon::createFromDate($year,$month,$i); 
                $a = (array)Carbon\Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
                $b = (array)Carbon\Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
//                $c = (array)\DB::table('ordenes')
//                        ->where('user_id','','')
//                        ->whereBetween('created_at', [$a, $b])->count();
//                $start['Semana: '.$j]= array_merge($a,$b,$c);
                $start['Semana: '.$j]= array_merge($a,$b);

                $i+=5;
                $j++; 
            }

            $result[$value->id."*".$value->rif."*".$value->nombre] = $start;
            //$result['numberOfWeeks'] = ["$numberOfWeeks"];
        }
        
        
        return view('admin.reporte_empresa_select',[
            'mensaje'=>'Reporte Semanal de Ordenes',
            'selsectEmpresa' => $result,
            'idJs' => $idJS,
                ]);
    }
    
    /**
     * Carga la iformacion de los pedidos por semana de la empresa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function freporte(Request $request)
    {
        dd($request->empresa);
        //a partir de la empresa cargamos los clientes con ordenes
    }
    
    public function reporte(Request $request, $id)
    {
        //dd($request->all());
        $rango = explode("*", $request->rango);
        //en donde 2018-03-31
        //$rango[0] = id empresa
        //$rango[1] = fecha inicio
        //$rango[2] = fecha fin
        $fromDate = $rango[1] . ' 00:00:00';
        $toDate = $rango[2] . ' 23:59:59';
        
        $ordenes = array();
        
        $clientes = \DB::table('clientes')
                ->where('empresa_id',$rango[0])
                ->get();
        if(count($clientes) == 0){
            return \Redirect::back()->withErrors('No existen clientes afiliados a esta empresa');
        }
        
        //verificamos si el cliente tiene pedidos realizados en el rango de fechas
        foreach($clientes as $cliente){
            $orden = \DB::table('ordenes')
                    ->join('users','ordenes.user_id','=','users.id')
                    ->join('menus','ordenes.menu_id','=','menus.id')
                    ->select('users.name AS nombreUser',
                            'menus.nombre AS nombreMenu',
                            'menus.seccion AS MenuSeccion',
                            'ordenes.*')
                    ->where('ordenes.user_id',$cliente->user_id)
                    ->whereBetween('ordenes.created_at', [$fromDate, $toDate])
                    ->get();
            
            $ordenes[] = $orden;
        }
        
        foreach($ordenes as $orden){
            if(count($orden)==0){return \Redirect::back()->withErrors('No existen datos para el rango de fechas '. $fromDate.' - '. $toDate);}
        }
        
        $recetas = \DB::table('recetas')->get();
        $empresa = \DB::table('empresa')->where('id',$rango[0])->get();
        return view('admin.reportes_consumo',['mensaje'=>'Reporte de Consumo','empresas'=>$empresa,'ordenes'=>$ordenes,'recetas'=>$recetas,'preriodo'=>$rango[1]." AL ".$rango[2]]);
    }
}
