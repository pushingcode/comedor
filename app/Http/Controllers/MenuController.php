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
