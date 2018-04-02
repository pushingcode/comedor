<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Schema;

use Illuminate\Console\Command;

use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Blueprint;

use BD;

class ImportMateriales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportMateriales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga a base de DATOS DE Materias Primas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info("Advertencia!!! El archivo CSV a ejecutar debe estar en la carpeta storage");
        $fileName = $this->ask("nombre de archivo?");
        
        //primer paso verificando si el archivo $filename
        if (is_file("/var/www/pedidos/app/storage/" . $fileName .".csv")) {

            $csv = array_map('str_getcsv', file('/var/www/pedidos/app/storage/'.$fileName . '.csv'));
            // paso verificando que la tabla existe y esta vacia

            if (!\Schema::hasTable($fileName)) {

                $this->info("La tabla ". $fileName ." no exixte. Se disparan los mecanismos de creacion");

                if ($this->confirm('Atencion, se creara la tabla '.$fileName.' ? [yes|no]')) {

                    $tableCreate = \Schema::create($fileName, function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('producto');
                        $table->string('calorias');
                        $table->string('proteinas');
                        $table->string('grasas');
                        $table->string('carbohidratos');
                    });

                    //despues de creada la tabla cargamos a partir de un array

                        $this->info("La tabla " . $fileName . " fue creada");

                        $this->info("Iniciando la carga de " . $fileName . "\r\n");

                        sleep(2);
                        $bar = $this->output->createProgressBar(count($csv));

                        foreach ($csv as $keyCSV => $valueCSV) {
                           // $this->info($valueCSV[0]);
                            \DB::table($fileName)->insert(
                                [
                                'producto'      => $valueCSV[0],
                                'calorias'      => $valueCSV[1],
                                'proteinas'     => $valueCSV[2],
                                'grasas'        => $valueCSV[3],
                                'carbohidratos' => $valueCSV[4]
                                ]
                            );

                            $bar->advance();
                            
                        }

                        $bar->finish();

                } else {

                    $this->info("La operacion fue abortada");

                }

            } else {

                //comprobando que la tabla esta vacia
                $checkTable = \DB::table($fileName)->count();
                
                if ($checkTable == count($csv)) {

                    $this->info("La cantidad de registro esta sin variar, verificando integridad");

                    sleep(2);

                    $bar = $this->output->createProgressBar(count($csv));

                    foreach ($csv as $key => $value) {

                        $match = \DB::table($fileName)
                        ->where('producto',$value[0])
                        ->count();
                        if($match == 1){

                            $bar->advance();

                        }else{

                            $this->info("tabla comprometida");
                            $bar->finish();

                        }
                    }

                    $bar->finish();

                }else{

                    foreach ($csv as $keyCSV => $valueCSV) {
                       
                        \DB::table($fileName)->insert(
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
        }else{

            $this->info("El archivo ". $fileName ." no existe");

        }

        $this->info("\nTerminado!");
    }
}
