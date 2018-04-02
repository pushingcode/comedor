<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class EmpresaController extends Controller
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
        $empresa = \App\Empresa::paginate(15);
        
        return view('admin.empresa_lista',[
            'mensaje'=>'Lista de empresas',
            'empresas'=>$empresa,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //ultimas 5empresas registradas
        $empresa = \App\Empresa::where('activo', '=', 'si')
                ->orderBy('created_at', 'ASC')
                ->take(5)
                ->get();
        
        return view('admin.empresas',[
            'mensaje'=>'Gestion de Empresas',
            'empresas'=>$empresa,
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
        $user = \Auth::user();

        if(!$user->can('crear empresa')) {
            return \Redirect::back()->withErrors('No posee roles para ejecutar esa accion');
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'rif' => 'required|max:100',
            'email' => 'required|email',
            'direccion' => 'required|max:100',
            'telefono' => 'required|max:100',
            'empleados' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return \Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }

        //guardamos la nueva empresa

        $empresa = new \App\Empresa;

            $empresa->nombre    = $request->nombre;
            $empresa->rif       = $request->rif;
            $empresa->email     = $request->email;
            $empresa->direccion = $request->direccion;
            $empresa->telefono  = $request->telefono;
            $empresa->empleados = $request->empleados;
            $empresa->activo    = $request->activo;

        $empresa->save();

        return \Redirect::back()->withErrors('Nueva empresa creada');
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
        $permiso = 'editar empresa';
        
        if (!$user->can($permiso)) {
            return Redirect::back()->withErrors('Este usuario no posee roles para '. $permiso);
        }
        $empresa = \DB::table('empresa')->where('id',$id)->get();
        //dd($empresa[0]->rif);
        return view('admin.empresa_editar',[
            'mensaje'   =>'Editando a la empresa '. $empresa[0]->nombre,
            'empresas'  =>$empresa,
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
        //
        $user = \Auth::user();
        
        $permiso = 'editar empresa';
        
        if (!$user->can($permiso)) {
            return Redirect::back()->withErrors('Este usuario no posee roles para '. $permiso);
        }
        
        \DB::table('empresa')
            ->where('id','=',$id)
            ->update([
            'nombre'    => $request->nombre,
            'rif'       => $request->rif,
            'email'     => $request->email,
            'direccion' => $request->direccion,
            'telefono'  => $request->telefono,
            'empleados' => $request->empleados,
            'activo'    => $request->activo,
            ]);
        
            return redirect()->action('HomeController@index')->withErrors('La empresa '. $request->nombre . ' ha sido editada');
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
        
        $permiso = 'eliminar empresa';
        
        if (!$user->can($permiso)) {
            return Redirect::back()->withErrors('Este usuario no posee roles para '. $permiso);
        }
        
        \App\Empresa::destroy($id);
        return redirect()->action('HomeController@index')->withErrors('Empresa eliminada');
    }
    
    /**
     * Update the specified resource in storage from Admin Control.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reedit(Request $request) {
        //
        $user = \Auth::user();
        
        //comprobando el metodo de operacion
       
        
        if (!$user->can('editar empresa')) {
            return Redirect::back()->withErrors('Este usuario no posee roles para editar empresa');
        }
        
        $empresa = \App\Empresa::where('id',$request->empresa)->get();
        //
        //dd($empresa);
        if(count($empresa) == 0){
            return Redirect::back()->withErrors('El recurso solicitado no existe');
        } else {
            return redirect()->action('EmpresaController@edit', ['id' => $empresa[0]->id]);
        }
        
    }
}
