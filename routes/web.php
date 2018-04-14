<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| Route::get('photos/popular', 'PhotoController@method');
|
*/

/*Route::get('/', function () {
    return view('auth.login');
});
*/
Route::view('/', 'auth.login');

Route::get('produccion/producir_receta/{id}','ProduccionController@build');
Route::get('recetas/sdestroy/{id}','RecetaController@sdestroy');
Route::get('entrega/menu/{id}','OrdenesController@entrega');
Route::get('semanal/empresas','OrdenesController@creporte');
Route::post('clientes/rif','ClientesController@rif');
Route::post('menu/activate/{id}','MenuController@activate');
Route::post('empresa/reedit','EmpresaController@reedit');
Route::post('clientes/registrar', 'ClientesController@registrar')->name('registrar');
Route::post('clientes/reedit','ClientesController@reedit');
Route::post('semanal/empresas','OrdenesController@freporte');
Route::post('semanal/cargar/reporte/{id}','OrdenesController@reporte');
Route::post('mensual/cargar/reporte/{id}','OrdenesController@mreporte');
Route::get('ordentest','OrdenesController@promreporte');
Route::post('comentarios', 'ClientesController@comentario');

//testCases
Route::get('reporte', 'PlanController@planreporte');


Route::resources([
	'recetas'	 	=> 'RecetaController',
	'produccion'            => 'ProduccionController',
	'plan'		 	=> 'PlanController',
	'menu'		 	=> 'MenuController',
	'clientes'	 	=> 'ClientesController',
	'empresa'	 	=> 'EmpresaController',
	'ordenes'		=> 'OrdenesController',
	]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
