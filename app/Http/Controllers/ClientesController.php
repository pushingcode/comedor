<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class ClientesController extends Controller
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
        /*
        * Paso 1 verificamos el tipo de usuario
        * SuperAdmin tiene acceso a todos los controles del sistema
        * para todas las empresas y para todos los usuarios cliente
        * --------------------------------------------------------------------------
        * Admin solo tendra acceso a todos los servicios que su empresa tenga activo
        * Podra Crear/Actualizar/Eliminar Clientes
        */

        //cargando al usuario autentificado
        
        $user = \Auth::user();

        if($user->hasRole('superadmin')) {

            //modelos de empresas y clientes registrados

            $empresas = \App\Empresa::all();
            $clientes = \App\Clientes::all();
            
            $clientesApp = \DB::table('clientes')
                    ->join('empresa','clientes.empresa_id','=','empresa.id')
                    ->join('users','clientes.user_id','=','users.id')
                    ->select('empresa.nombre AS nombreEmpresa',
                            'empresa.id AS idEmpresa',
                            'empresa.email AS emailEmpresa',
                            'users.id AS idUser',
                            'users.name AS nameUser',
                            'users.email AS emailUser',
                            'clientes.cargo AS clienteCargo',
                            'clientes.created_at')
                    ->where('clientes.activo','=','si')
                    ->where('clientes.deleted_at','=',null)
                    ->get();
            
            //dd($clientesApp);
            
            $userApp = \DB::table('users')
                            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
                            ->select('users.name','users.id','model_has_roles.role_id AS rol')
                            ->where('model_has_roles.model_type','=','App\User')
                            ->get();
            //consultando si clientes admin esta registrados como clientes

            $users = \App\User::role('admin')->get();
            $flagA = array();
            foreach ($users as $userAdm) {
                $buscaCliente = \DB::table('clientes')
                        ->where([
                            ['user_id','=',$userAdm->id],
                            ['activo','=','si'],
                            ['deleted_at','=',null],
                        ])->get();

                if(count($buscaCliente) == 0) {
                    $flagA[] =  $userAdm->name."|".$userAdm->id;
                }
            }


            return view('admin.empresas',[
                'mensaje'           => 'Gestion de Empresas',
                'empresas'          => $empresas,
                'clientesApp'      => $userApp,
                'clientes'          => $clientes,
                'clientesEmp'       => $clientesApp,
                'extras'            => $flagA
                ]);

        } elseif ($user->hasRole('admin')) {
             //dd("solo admin");
        } else {
            return  view('home');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $empresas = \App\Empresa::all();
        return  view('admin.usuario_crear',['empresas' => $empresas]);
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
        //dd($request->usuario);
        $idForm = explode("-",$request->usuario);
        switch ($idForm[1]) {
            case 3:
                $cargo = "admin";
                break;
            case 4:
                $cargo = "usuario";
                break;
        }
        
        $user = \App\User::find($idForm[0]);
        $user->assignRole($cargo);
        
        $cliente = new \App\Clientes;

            $cliente->user_id       = $idForm[0];
            $cliente->empresa_id    = $request->empresa;
            $cliente->cargo         = $cargo;

        $cliente->save();

        return \Redirect::back()->withErrors('Cliente asignado');
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
        $user = \Auth::user();
        
        //dd($empresas);
        if($user->hasRole('superadmin')) {
            //
            $users = \DB::table('clientes')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->join('empresa', 'clientes.empresa_id', '=', 'empresa.id')
            ->select('users.id AS userId',
                    'users.name AS userNombre',
                    'users.email AS userEmail',
                    'clientes.id AS idCliente',
                    'clientes.cargo AS userCargo',
                    'clientes.activo AS userActivo',
                    'empresa.id AS empresaID',
                    'empresa.nombre AS empresaNombre',
                    'empresa.rif AS empresaRif')
            ->where('clientes.user_id', '=', $id)
            ->get();
        
            $empresas = \App\Empresa::whereNotIn('id',[$users[0]->empresaID])->get();
            return view('admin.clientes_editar',[
                'mensaje'   => 'Editando al cliente '. $users[0]->userNombre,
                'users'     => $users,
                'empresas'  => $empresas,]);
            
        } elseif($user->hasRole('admin')) {
            //
            $selfCliente = \DB::table('clientes')
                    ->where('user_id','=',$user->id)
                    ->get();
            
            $users = \DB::table('clientes')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->join('empresa', 'clientes.empresa_id', '=', 'empresa.id')
            ->select('users.id AS userId',
                    'users.name AS userNombre',
                    'users.email AS userEmail',
                    'clientes.id AS idCliente',
                    'clientes.cargo AS userCargo',
                    'clientes.activo AS userActivo',
                    'empresa.id AS empresaID',
                    'empresa.nombre AS empresaNombre',
                    'empresa.rif AS empresaRif')
            ->where([['clientes.user_id', '=', $id],['clientes.empresa_id', '=', $selfCliente[0]->id]])
            ->get();
            
            $empresas = \App\Empresa::where('id','=',$selfCliente[0]->id)->get();
            
            return view('admin.clientes_editar',[
                'mensaje'   => 'Editando al cliente '. $users[0]->userNombre,
                'users'     => $users,
                'empresas'  => $empresas,]);
        }
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
        $user = \Auth::user();
        
        $permiso = 'editar cliente';
        
        if (!$user->can($permiso)) {
            return \Redirect::back()->withErrors('Este usuario no posee permisos para '. $permiso);
        }
        
        \DB::table('clientes')
            ->where('id','=',$id)
            ->update([
                'empresa_id'=>$request->empresa,
                'cargo'     => $request->representante,
                'activo'    => $request->activo,
                
            ]);
        
            return redirect()->action('HomeController@index')->withErrors('Cliente '. $request->nombre . ' ha sido editada');
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
        
        $permiso = 'eliminar cliente';
        
        if (!$user->can($permiso)) {
            return Redirect::back()->withErrors('Este usuario no posee permiso para '. $permiso);
        }
        
        \App\Clientes::destroy($id);
        return redirect()->action('HomeController@index')->withErrors('Cliente eliminado');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rif(Request $request) {
        //
        $id = \Auth::id();
        $timer = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        //comprobando si el rif existe
        $rif = \DB::table('empresa')
                ->where('rif','=',$request->rif)
                ->get();
        if(count($rif) == 0) 
            {
            $mensaje = "El rif no existe registrado";
            } else{
                \DB::table('clientes')
                ->insert([
                    'user_id'       => $id,
                    'empresa_id'    => $rif[0]->id,
                    'cargo'         => "usuario",
                    'activo'        => "no",
                    'created_at'    => $timer,
                    'updated_at'    => $timer,
                ]);
             $mensaje = "Usuario Agregado a " . $rif[0]->nombre;
            }
        
        return \Redirect::back()->withErrors($mensaje);
    }
    
    public function registrar(Request $request) {
        //
        //ToDo
        //validacion de usuario
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return \Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
        //registro de usuario
        
        //registro de cliente
        //consultando la base de empresas para validacion y mensage 
        $timer = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        //comprobando si el rif existe
        $rif = \DB::table('empresa')
                ->where('id','=',$request->empresa)
                ->get();
        if(count($rif) == 0) 
            {
            $mensaje = "La empresa no existe registrada";
            } else{
                $id = \DB::table('users')
                ->insertGetId([
                   'name'           => $request->name,
                   'email'          => $request->email,
                   'password'       => bcrypt($request->password),
                   'created_at'     => $timer,
                   'updated_at'     => $timer,
               ]);
                
                if(!$id) {
                    $mensaje = "Error al crear el cliente";
                    return \Redirect::back()->withErrors($mensaje);
                }
                
                \DB::table('clientes')
                ->insert([
                    'user_id'       => $id,
                    'empresa_id'    => $rif[0]->id,
                    'cargo'         => $request->representante,
                    'activo'        => $request->activo,
                    'created_at'    => $timer,
                    'updated_at'    => $timer,
                ]);
                
                $user = \App\User::find($id);
                $user->assignRole($request->representante);
                
                $mensaje = $request->name . " Agregado a " . $rif[0]->nombre;
            }
        return \Redirect::back()->withErrors($mensaje);
        
    }
    
    public function reedit(Request $request) {
        //
        $user = \Auth::user();
        
        //comprobando el metodo de operacion
       
        
        if (!$user->can('editar cliente')) {
            return \Redirect::back()->withErrors('Este usuario no posee roles para editar clientes');
        }
        
        $users = \DB::table('clientes')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->join('empresa', 'clientes.empresa_id', '=', 'empresa.id')
            ->select('users.id AS userId',
                    'users.name AS userNombre',
                    'users.email AS userEmail',
                    'clientes.cargo AS userCargo',
                    'clientes.activo AS userActivo',
                    'empresa.*')
            ->where('clientes.user_id', '=', $request->usuario)
            ->get();
        
    //dd($users);
        if(count($users) == 0){
            return \Redirect::back()->withErrors('El recurso solicitado no existe');
        } else {
            return redirect()->action('ClientesController@edit', ['id' => $users[0]->userId]);
        }
        
    }
    
    public function comentario(Request $request) {
        $user = \Auth::user();
        $empresa = \DB::table('clientes')->where('user_id',$user->id)->get();
        //dd($empresa);
        $validator = \Validator::make($request->all(), [
            'comentario' => 'required|max:1000',
        ]);
        
        if ($validator->fails()) {
            return \Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $timer = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        \DB::table('comentarios')->insert([
            'user_id'       => $user->id,
            'empresa_id'    =>$empresa[0]->empresa_id,
            'comentario'    =>$request->comentario,
            'activo'        =>'si',
            'created_at'    => $timer,
            'updated_at'    => $timer
        ]);
        return \Redirect::back()->withErrors('tu comentario fue enviado');
    }
}
