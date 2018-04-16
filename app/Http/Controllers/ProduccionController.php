<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Produccion;
use App\Receta;

class ProduccionController extends Controller
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
        /**
         * Carga de todos los trabajos de produccion 
         */
        $produccion = \DB::table('produccion')
                    ->join('recetas','recetas.id','=','produccion.id_r')
                    ->join('users', 'users.id', '=', 'produccion.user_id')
                    ->select('produccion.*','recetas.nombre','users.name')
                    ->where('produccion.cantidad_s','=',0)
                    ->get();
       

        //
        
        //enviando a la vista
        return view('admin.produccionIndex', [
            'mensaje'   => 'Produccion Activa',
            'producciones'=>  $produccion
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
        $fail = false;
        $flag = array();
        $index = array_slice($request->all(),3,30,true);
        
        if($request->batch == 0){
            
            return \Redirect::back()->withErrors('No es posible cargar esa cantidad de porciones!!!');
            
        }

        //$request->receta; //id de la receta
        //$request->batch; //cantidad de veces o platos que seran producidos
        //consultando las cantidades en el inventario

        foreach ($index as $key => $value) {
            
            $inventario = DB::table('invetarios')
                    ->where('id_p', $key)
                    ->get();
            if(count($inventario) == 0){
                return \Redirect::back()->withErrors('No existe inventario, por favor instale uno mediante el comando Inventario (*)');
            }
            $chekInv = $inventario[0]->cantidad - ($request->batch * $value);
            //armando el paquete de actualizacion de inventario
            //validando que las cantidades no sean negativas
            
            if ($chekInv > 100) {
                
                $flag[$key] = array("estado" => "t", "posInv" => $chekInv);
                
            } else {
                
                $flag[$key] = array("estado" => "f", "posInv" => $chekInv);
                
                $fail = true;
            }
           
        }
        if($fail) {

            return \Redirect::back()->withErrors('El batch de produccion es mayor al inventario');
            
        } else {
            
            //restando del inventario
            foreach ($flag as $keyA => $valueA) {
                \DB::table('invetarios')
                    ->where('id_p', $keyA)
                    ->update([
                        'cantidad' => $valueA['posInv']
                    ]);
            }
            /*
             * cargando nuevo proceso de produccion
             * fecha de entrada
             * codigo de produccion
             */
            $codigo = "REC-" . $request->receta."-". time();
            $user = \Auth::id();
            
            $produccion = new \App\Produccion;

            $produccion->id_r = $request->receta;
            $produccion->cantidad_e = $request->batch;
            $produccion->cantidad_s = 0;
            $produccion->codigo = $codigo;
            $produccion->user_id = $user;

            $produccion->save();
            
            
            
            
            //return "Tablas produccion e inventario colicionadas";
            return redirect('produccion')->with([
                'mensaje' => 'Carga completada con el codigo '.$codigo
                ]);
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
     * Show the form for creating the specified recipe.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function build($id)
    {
        //
        //dd($id);
        $recetas = DB::table('recetas')
                 ->where('id', $id)
                 ->get();

        foreach ($recetas as $receta) {
            $decode = json_decode($receta->receta, true);
            $recetaA[$receta->nombre."|".$receta->id] = $decode;

        }
//dd($decode);
        $x = 0;
        for($i = 1; $i <= count($decode); $i++){
            //$cargaInventario[$decode["producto".$i]["id"]] = $decode["producto".$i]["cantidad"];
            $consultaInventario[] = $decode[$x]["producto".$i]["id"];
            $x++;
        }
        $inventario = DB::table('invetarios')
                            ->whereIn('id',$consultaInventario)
                            ->get();

        //dd($inventario);
        //enviando info a vista formulario para confirmar las cantidades

        /*return view('admin.recetas', [
            'mensaje'       => 'Recetas',
            'recetas'       => $recetaA,
            'inventarios'    => $inventario
            ]);*/

        return view('admin.build', [
            'mensaje'   => 'Carga de produccion para '.$receta->nombre,
            'recetas'=> $recetaA,
            'inventarios'=> $inventario]);

    }
}
