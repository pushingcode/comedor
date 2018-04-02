<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Blueprint;

use BD;

class ImportInventario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Inventario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea el inventario de pruebas del sistema';

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
        $archivo = dirname(__DIR__, 3).'/storage/inventarioInicial.csv';
        if (is_file($archivo)) {
            $csv = array_map('str_getcsv', file($archivo));
            if (\Schema::hasTable('invetarios')) {
                if ($this->confirm('Atencion, se creara el inventario de PRUEBAS? [yes|no]')) {
                    //
                    $bar = $this->output->createProgressBar(count($csv));

                        foreach ($csv as $keyCSV => $valueCSV) {
                           // $this->info($valueCSV[0]);
                            \DB::table('invetarios')->insert(
                                [
                                'id_p'          => $valueCSV[0],
                                'cantidad'      => $valueCSV[1]
                                ]
                            );

                            $bar->advance();
                            
                        }

                        $bar->finish();

                        $this->info("\nFinalizado");
                } else {
                    $this->info("\nAccion abortada por el usuario");
                }
            } else {
                $this->info("Error... la tabla no existe, debe generar las migraciones antes de seguir");
            }
        } else {
            $this->info("Error... El archivo no existe". $archivo);
        }
    }
}
