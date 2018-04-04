<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//use DB;
//use Recetas;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.RolesAndPermissionsSeeder
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('MateriaTableSeeder');
        $this->command->info('User table seeded!');

    	$this->call('RecetasTableSeeder');
        $this->command->info('Recetas table seeded!');

        $this->call('RolesAndPermissionsSeeder');
        $this->command->info('Roles y Permisos table seeded!');
        
    }
}

/**
* 
*/
class UsersTableSeeder extends Seeder 
{
    public function run()
    {
       //corriendo seeding para users
        $timer      = Carbon\Carbon::now()->format('Y-m-d H:i:s');
        
        $users =[
        'usuario1' => array(
                'user'      => 'User Admin',
                'email'     => 'code_dev@zoho.com',
                'password'  => bcrypt('123456')
                ),
        'usuario2' => array(
                'user'      => 'Carlos Martinez',
                'email'     => 'cmartinez@gmail.com',
                'password'  => bcrypt('123456')
                ),
        'usuario3' => array(
                'user'      => 'User Test1',
                'email'     => 'phpunit1@gmail.com',
                'password'  => bcrypt('123456')
                ),
        'usuario4' => array(
                'user'      => 'User Test2',
                'email'     => 'phpunit2@gmail.com',
                'password'  => bcrypt('123456')
                )
        ];

        foreach ($users as $value) {
            \DB::table('users')->insert([
            'name'      => $value['user'],
            'email'     => $value['email'],
            'password'  => $value['password'],
            'created_at'    => $timer,
            'updated_at'    => $timer
            ]);
        }
        
        
    }
}

/**
* 
*/
class MateriaTableSeeder extends Seeder
{
    
    public function run()
    {
        //corriendo seeding para materia_prima
         $csv = array_map('str_getcsv', file(dirname(__DIR__, 2).'/storage/materiaPrima.csv'));
         foreach ($csv as $keyCSV => $valueCSV) {
           // $this->info($valueCSV[0]);
            \DB::table('materia_prima')->insert(
                [
                'producto'      => $valueCSV[0],
                'calorias'      => $valueCSV[1],
                'proteinas'     => $valueCSV[2],
                'grasas'        => $valueCSV[3],
                'carbohidratos' => $valueCSV[4]
                ]
            );
            
        }
    }
}

/**
* 
*/
class RecetasTableSeeder extends Seeder 
{

	public function run()
	{
            
    // corriendo seeding de pruebas para recetas

        /*$json ='{"producto1":{"id":"33","nombre":"Cerdo carne magra","calorias":"146.00","proteinas":"19.90","grasas":"6.8","carbohidratos":"0","cantidad":"170"},"producto2":{"id":"10","nombre":"Cebolla","calorias":"24","proteinas":"1","grasas":"0.1","carbohidratos":"5.2","cantidad":"70"},"producto3":{"id":"29","nombre":"Zanahoria","calorias":"37.00","proteinas":"1","grasas":"0.2","carbohidratos":"7.8","cantidad":"90"}}';*/


        //seteamos el valor de producto
        

            $productoPlato1[] = array(
                'producto1'       => array(
                        'id'            => "33",
                        'nombre'        => "Cerdo carne magra",
                        'calorias'      => "146.00",
                        'proteinas'     => "19.90",
                        'grasas'        => "6.8",
                        'carbohidratos' => "0",
                        'cantidad'      => "170"
                        ),
                'producto2'       => array(
                        'id'            => "10",
                        'nombre'        => "Cebolla",
                        'calorias'      => "24",
                        'proteinas'     => "1",
                        'grasas'        => "01",
                        'carbohidratos' => "5.2",
                        'cantidad'      => "70"
                        ),
                'producto3'       => array(
                        'id'            => "29",
                        'nombre'        => "Zanahoria",
                        'calorias'      => "37.00",
                        'proteinas'     => "1",
                        'grasas'        => "0.2",
                        'carbohidratos' => "7.8",
                        'cantidad'      => "90"
                        ),
                );

            $productoPlato2[] = array(
                'producto1'       => array(
                        'id'            => "49",
                        'nombre'        => "Pollo pechuga",
                        'calorias'      => "108.00",
                        'proteinas'     => "22.40",
                        'grasas'        => "2.1",
                        'carbohidratos' => "0",
                        'cantidad'      => "140"
                        ),
                'producto2'       => array(
                        'id'            => "10",
                        'nombre'        => "Cebolla",
                        'calorias'      => "24",
                        'proteinas'     => "1",
                        'grasas'        => "01",
                        'carbohidratos' => "5.2",
                        'cantidad'      => "70"
                        ),
                'producto3'       => array(
                        'id'            => "29",
                        'nombre'        => "Zanahoria",
                        'calorias'      => "37.00",
                        'proteinas'     => "1",
                        'grasas'        => "0.2",
                        'carbohidratos' => "7.8",
                        'cantidad'      => "90"
                        ),
                );

                $productoPlato3[] = array(
                'producto1'       => array(
                        'id'            => "1",
                        'nombre'        => "Arroz",
                        'calorias'      => "130",
                        'proteinas'     => "7.2",
                        'grasas'        => "0.3",
                        'carbohidratos' => "28.2",
                        'cantidad'      => "100"
                        ),
                );

                $productoPlato4[] = array(
                'producto1'       => array(
                        'id'            => "164",
                        'nombre'        => "Platano Frito",
                        'calorias'      => "252",
                        'proteinas'     => "1.47",
                        'grasas'        => "13.27",
                        'carbohidratos' => "36.02",
                        'cantidad'      => "100"
                        ),
                );


        $carga =  [
            "plato3" => array(
                                "receta" => "Arroz Blanco cocido",
                                "tipo"   => "contorno",
                                "load"   => $productoPlato3
                                ),
            "plato4" => array(
                                "receta" =>"Tajadas de Platano",
                                "tipo"   =>"contorno",
                                "load"   => $productoPlato4
                                ),
                ];

        $timer      = Carbon\Carbon::now()->format('Y-m-d H:i:s');
        foreach ($carga as $value) {
            \DB::table('recetas')->insert([
            'nombre'        => $value['receta'],
            'tipo'          => $value['tipo'],
            'receta'        => json_encode($value['load']),
            'user_id'       => 1,
            'created_at'    => $timer,
            'updated_at'    => $timer
            ]);
        }
        
        
        
        
	}
}

/**
* 
*/
class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'crear usuario']);
        Permission::create(['name' => 'editar usuario']);
        Permission::create(['name' => 'eliminar usuario']);
        Permission::create(['name' => 'activar usuario']);
        Permission::create(['name' => 'desactivar usuario']);
        Permission::create(['name' => 'crear menu']);
        Permission::create(['name' => 'editar menu']);
        Permission::create(['name' => 'eliminar menu']);
        Permission::create(['name' => 'activar menu']);
        Permission::create(['name' => 'desactivar menu']);
        Permission::create(['name' => 'crear plan']);
        Permission::create(['name' => 'eliminar plan']);
        Permission::create(['name' => 'activar plan']);
        Permission::create(['name' => 'desactivar plan']);
        Permission::create(['name' => 'crear produccion']);
        Permission::create(['name' => 'eliminar produccion']);
        Permission::create(['name' => 'activar produccion']);
        Permission::create(['name' => 'desactivar produccion']);
        Permission::create(['name' => 'crear receta']);
        Permission::create(['name' => 'editar receta']);
        Permission::create(['name' => 'eliminar receta']);
        Permission::create(['name' => 'actualizar receta']);
        Permission::create(['name' => 'crear pedido']);
        Permission::create(['name' => 'editar pedido']);
        Permission::create(['name' => 'eliminar pedido']);
        Permission::create(['name' => 'actualizar pedido']);
        Permission::create(['name' => 'crear empresa']);
        Permission::create(['name' => 'editar empresa']);
        Permission::create(['name' => 'eliminar empresa']);
        Permission::create(['name' => 'actualizar empresa']);
        Permission::create(['name' => 'crear cliente']);
        Permission::create(['name' => 'editar cliente']);
        Permission::create(['name' => 'eliminar cliente']);
        Permission::create(['name' => 'actualizar cliente']);

        // create roles and assign existing permissions
        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo([
            'crear usuario',
            'editar usuario',
            'eliminar usuario',
            'activar usuario',
            'desactivar usuario',
            'crear menu',
            'editar menu',
            'eliminar menu',
            'activar menu',
            'desactivar menu',
            'crear plan',
            'eliminar plan',
            'activar plan',
            'desactivar plan',
            'crear produccion',
            'eliminar produccion',
            'activar produccion',
            'desactivar produccion',
            'crear receta',
            'editar receta',
            'eliminar receta',
            'actualizar receta',
            'crear pedido',
            'editar pedido',
            'eliminar pedido',
            'actualizar pedido',
            'crear empresa',
            'editar empresa',
            'eliminar empresa',
            'actualizar empresa',
            'crear cliente',
            'editar cliente',
            'eliminar cliente',
            'actualizar cliente'
            ]);

        $role = Role::create(['name' => 'jefe de cocina']);
        $role->givePermissionTo([
            'crear menu',
            'editar menu',
            'eliminar menu',
            'activar menu',
            'desactivar menu',
            'crear plan',
            'eliminar plan',
            'activar plan',
            'desactivar plan',
            'crear produccion',
            'eliminar produccion',
            'activar produccion',
            'desactivar produccion',
            'crear receta',
            'editar receta',
            'eliminar receta',
            'actualizar receta',
            'crear pedido',
            'editar pedido',
            'eliminar pedido',
            'actualizar pedido'
            ]);
        
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'crear empresa',
            'editar empresa',
            'eliminar empresa',
            'actualizar empresa',
            'crear cliente',
            'editar cliente',
            'eliminar cliente',
            'actualizar cliente',
            'crear pedido',
            'editar pedido',
            'eliminar pedido',
            'actualizar pedido'
            ]);

        $role = Role::create(['name' => 'usuario']);
        $role->givePermissionTo([
            'crear pedido',
            'editar pedido',
            'eliminar pedido',
            'actualizar pedido'
            ]);


        $user = \App\User::find(1);
        
        $user->assignRole('superadmin');

        $user1 = \App\User::find(2);

        $user1->assignRole('jefe de cocina');

        $user2 = \App\User::find(3);

        $user2->assignRole('admin');

        $user3 = \App\User::find(4);

        $user3->assignRole('usuario');

        //$role = Role::create(['name' => 'admin']);
        //$role->givePermissionTo(['publish articles', 'unpublish articles']);
    }
}