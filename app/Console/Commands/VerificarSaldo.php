<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpDetalles;
use App\Models\AlpInventario;
use App\Models\AlpConfiguracion;
use App\Models\AlpSaldo;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;

use Illuminate\Console\Command;

class VerificarSaldo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:saldo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LLevar el saldo vencido a 0';

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
        $configuracion=AlpConfiguracion::where('id', '1')->first();

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $clientes=AlpSaldo::whereDate('fecha_vencimiento', '<', $hoy)->where('estado_registro', '1')->groupBy('id_cliente')->get();

        \Log::debug('Clientes a verificar saldo' . $clientes);

       // dd($clientes);

        $inv=$this->getSaldo();

        foreach ($clientes as $c) {
            # code...
            if (isset($inv[$c->id_cliente])) {

                $s=AlpSaldo::where('id', $c->id)->first();

                $s->update(['estado_registro'=>'0']);

                $data = array(
                    'id_cliente' => $c->id_cliente, 
                    'saldo' => $inv[$c->id_cliente], 
                    'operacion' => '2', 
                    'fecha_vencimiento' => $c->fecha_vencimiento, 
                    'nota' => 'Ajuste de Saldo por vencimiento', 
                    'estado_registro' => 0 
                );

                AlpSaldo::create($data);

            }

        }

    }


    private function getSaldo()
    {
       
      $entradas = AlpSaldo::groupBy('id_cliente')
              ->select("alp_saldo.*", DB::raw(  "SUM(alp_saldo.saldo) as cantidad_total"))
              ->where('alp_saldo.operacion', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_cliente]=$row->cantidad_total;

              }


            $salidas = AlpSaldo::groupBy('id_cliente')
              ->select("alp_saldo.*", DB::raw(  "SUM(alp_saldo.saldo) as cantidad_total"))
              ->where('alp_saldo.operacion', '2')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_cliente]= $inv[$row->id_cliente]-$row->cantidad_total;

            }

            return $inv;
      
    }




}
