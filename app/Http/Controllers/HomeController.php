<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //enviando la vista home con un poco de informacion de recetas y produccines
        if (\Auth::check()) {

            $user = \Auth::user();
            $return = redirect()->action('OrdenesController@index');
            if($user->hasRole('superadmin|jefe de cocina')) {
                $recetas        = \App\Receta::all();
                $producciones   = \App\Produccion::all();
                $plan           = \App\Plan::all();
                $menus          = \App\Menu::all();
                $empresas       = \App\Empresa::all();
//              $users          = \App\Clientes::all();
                $users          = \DB::table('clientes')
                                ->join('users', 'clientes.user_id', '=', 'users.id')
                                ->select('users.id AS userId',
                                        'users.name AS userNombre',
                                        'users.email AS userEmail',
                                        'clientes.cargo AS userCargo')
                                ->where('clientes.deleted_at', '=', null)
                                ->get();

                $return = view('home',[
                        'recetas'       => $recetas,
                        'producciones'  => $producciones,
                        'planes'        => $plan,
                        'menus'         => $menus,
                        'empresas'      => $empresas,
                        'users'         => $users,
                        ]);
            }

            if($user->hasRole('admin')) {

                $return = redirect()->action('OrdenesController@index');
            }
        }
        return $return;
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        \Auth::logout();
        return view('home');
    }
}
