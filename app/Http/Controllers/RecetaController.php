<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecetaController extends Controller
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
        //carga de recetas activas existentes

        $recetas = \DB::table('recetas')
            ->where('deleted_at','=',null)
            ->get();

            foreach ($recetas as $receta) {
                $decode = json_decode($receta->receta, true);
                $recetaA[$receta->nombre."|".$receta->id."|".$receta->tipo."|".$receta->created_at] = $decode;
                $existencia[] = $decode;
                //dd($receta->receta); 

            }

            //dd($existencia);

            //preparando el arreglo para consulta


            foreach ($existencia as $values) {
                foreach($values[0] as $value){

                    $idProductos[] = $value["id"];
                    
                }
            }
           //print_r($idProductos);
           //print_r($recetaA);
            //consultando si existen productos en el inventario
            $inventario = \DB::table('invetarios')
                            ->whereIn('id_p', $idProductos)
                            ->get();


        return view('admin.index_recetas', [
            'mensaje'       => 'Recetas',
            'recetas'       => $recetaA,
            'inventarios'    => $inventario
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //cargando la informacion de materia_prima
        $materia_prima = \DB::table('materia_prima')
                            ->orderBy('producto', 'asc')
                            ->get();
        //dd($materia_prima);
        return view('admin.recetas_nueva', [
            'mensaje'               => 'Recetas',
            'materia_primas'        => $materia_prima
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
        $id_user = Auth::id();
        $user = \App\User::find($id_user);

        if (!$user->can('crear receta')) {
            return \Redirect::back()->withErrors('Este usuario no posee roles para crear recetas');
        }
        //
        /**
         * paso 1
         * ordenamiento y validacion de datos
         * paso 2
         * construccion del json con la siguiente estructura: 
         * a partir de la consulta de materias primas
            {
            "producto1": 
                {
                "id": "49", 
                "grasas": "2.1", 
                "nombre": "Pollo pechuga",
                "calorias": "108.00",
                "cantidad": "140",
                "proteinas": "22.40",
                "carbohidratos": "0"
                },
            "producto2": 
                {
                "id": "10",
                "grasas": "0.1",
                "nombre": "Cebolla",
                "calorias": "24",
                "cantidad": "70",
                "proteinas": "1",
                "carbohidratos": "5.2"
                },
            "producto3": 
                {"id": "29",
                "grasas": "0.2",
                "nombre": "Zanahoria",
                "calorias": "37.00",
                "cantidad": "90",
                "proteinas": "1",
                "carbohidratos": "7.8"
                }
            }

         *********************************
         * preparando los datos de entrada
         * $index = array_slice($request->all(),3,30,true);
         */
        $productos = array_slice($request->all(),4,34,true);

        //contando la cantidad de entrada del arreglo productos
        if(count($productos) == 0) {
            return \Redirect::back()->withErrors('Las cantidades no satisfacen las condiciones');
        } 

        $messages = [
            'required' => 'El camppo :attribute es requerido.',
            'max'      => 'El campo :attribute supera el maximo permitido'
        ];

        $validator = \Validator::make($request->all(), [
            'nombre' => 'required|max:100',
        ], $messages);

        if ($validator->fails()) {
            return \Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }


        //seteamos el valor de producto
        $i = 1;
        foreach ($productos as $key => $value) {

            if ($value == 0 || empty($value)) {
                return \Redirect::back()->withErrors('Las cantidades no satisfacen las condiciones');
            }

            $materia = \DB::table('materia_prima')
                            ->where('id','=',$key)
                            ->get();
                            //dd($materia);

            $producto[] = array(
                'producto'.$i       => array(
                    'id'            => $key,
                    'nombre'        => $materia[0]->producto,
                    'calorias'      => ($materia[0]->calorias * $value)/100,
                    'proteinas'     => ($materia[0]->proteinas * $value)/100,
                    'grasas'        => ($materia[0]->grasas * $value)/100,
                    'carbohidratos' => ($materia[0]->carbohidratos * $value)/100,
                    'cantidad'      => $value
                    ),
                );
            $i++;
        }


        $timer = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        //receta creada bajo parametros
         \DB::table('recetas')->insert([
            'nombre'        => $request->nombre,
            'tipo'          => $request->tipo,
            'receta'        => json_encode($producto),
            'user_id'       => $id_user,
            'created_at'    => $timer,
            'updated_at'    => $timer
            ]);

        return redirect()->action('RecetaController@index');
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
        $recetas = DB::table('recetas')
            ->where('id','=',$id)
            ->get();

        $materia_prima = \DB::table('materia_prima')
            ->orderBy('producto', 'asc')
            ->get();

        return view('admin.recetas_editar', [
            'mensaje'               => 'Edicion de Receta ',
            'recetas'               => $recetas,
            'materia_primas'        => $materia_prima
        ]);

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
        $id_user = Auth::id();
        $user = \App\User::find($id_user);

        if (!$user->can('editar receta')) {
            return \Redirect::back()->withErrors('Este usuario no posee roles para editar recetas');
        }

        //cortando el arreglo $request->all()
        $productos = array_slice($request->all(),5,35,true);
        //
        if(count($productos) == 0) {
            return \Redirect::back()->withErrors('Las cantidades no satisfacen las condiciones');
        }

        $messages = [
            'required' => 'El camppo :attribute es requerido.',
            'max'      => 'El campo :attribute supera el maximo permitido'
        ];

        $validator = \Validator::make($request->all(), [
            'nombre' => 'required|max:100',
        ], $messages);

        if ($validator->fails()) {
            return \Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $i = 1;
        foreach ($productos as $key => $value) {

            if ($value == 0 || empty($value)) {
                return \Redirect::back()->withErrors('Las cantidades no satisfacen las condiciones');
            }

            $materia = \DB::table('materia_prima')
                            ->where('id','=',$key)
                            ->get();
                            //dd($materia);

            $producto[] = array(
                'producto'.$i       => array(
                    'id'            => $key,
                    'nombre'        => $materia[0]->producto,
                    'calorias'      => ($materia[0]->calorias * $value)/100,
                    'proteinas'     => ($materia[0]->proteinas * $value)/100,
                    'grasas'        => ($materia[0]->grasas * $value)/100,
                    'carbohidratos' => ($materia[0]->carbohidratos * $value)/100,
                    'cantidad'      => $value
                    ),
                );
            $i++;
        }

        $timer = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        
        DB::table('recetas')
            ->where('id','=',$id)
            ->update([
            'nombre'        => $request->nombre,
            'tipo'          => $request->tipo,
            'receta'        => json_encode($producto),
            'user_id'       => $id_user,
            'updated_at'    => $timer
            ]);
            return \Redirect::back();

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
        $id_user = Auth::id();
        $user = \App\User::find($id_user);

        if (!$user->can('eliminar receta')) {
            return \Redirect::back()->withErrors('Este usuario no posee roles para eliminar recetas');
        }
        Log::info('El usuario ' . $user->name . " utilizo el metodo SoftDelete sobre la receta ID: " .$id);
        $receta = \App\Receta::find($id);
        $receta->delete();
        return \Redirect::back();
    }

    public function sdestroy($id)
    {
        //
        $id_user = Auth::id();
        $user = \App\User::find($id_user);

        if (!$user->can('eliminar receta')) {
            return Redirect::back()->withErrors('Este usuario no posee roles para eliminar recetas');
        }
        Log::info('El usuario ' . $user->name . " utilizo el metodo SoftDelete sobre la receta ID: " .$id);
        $receta = \App\Receta::find($id);
        $receta->delete();
        return response()->json([
            'receta' => $id,
            'estado' => 'Eliminada'
        ]);
    }
}
