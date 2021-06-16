<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Mail;
use DB;



use Illuminate\Console\Command;

class Pruebascron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:pruebas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancelar ordenes que esten vencidas segun tiempo establecido en configiracion ';

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
        

          \Log::debug('Ejecucion Cron de Prueba');

         // dd('1');


     


    }//end funcion

    
}
