<?php

namespace App\Mail;

use App\Models\AlpEnvios;
use App\Models\AlpFormasenvio;
use App\Models\AlpClientes;
use App\Models\AlpDirecciones;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompraSac extends Mailable
{
    use Queueable, SerializesModels;


    public $compra;
    public $detalles;
    public $fecha_entrega;
    public $envio;
    public $asunto;
    public $formaenvio;
    public $cliente;
    public $direccion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($compra, $detalles, $fecha_entrega, $asunto = 0)
    {
        //
        $this->compra=$compra;
        $this->detalles=$detalles;
        $this->fecha_entrega=$fecha_entrega;
        $this->envio=AlpEnvios::where('id_orden', $compra->id)->first();

        if ($asunto==0) {

            $asunto= 'Nuevo Pedido Nro.: '.$this->compra->id.' | SAC';

        }else{

          if($this->compra->estatus_pago==1){

            $asunto= ' ENVIO EXPRESS | Nuevo Pedido Nro.: '.$this->compra->id.' | En Espera de Pago ';

          }elseif($this->compra->estatus_pago==2){

            $asunto= ' ENVIO EXPRESS | Nuevo Pedido Nro.: '.$this->compra->id.' | Pago recibido ';

          }elseif($this->compra->estatus_pago==3){

            $asunto= ' ENVIO EXPRESS | Nuevo Pedido Nro.: '.$this->compra->id.' | Pago cancelado ';

          }elseif($this->compra->estatus_pago==4){

            $asunto= ' ENVIO EXPRESS | Nuevo Pedido Nro.: '.$this->compra->id.' | En Espera de Pago ';

          }

         


            
        }

        $this->asunto=$asunto;

        $this->formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

        $this->direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id', '=', $compra->id_address)->withTrashed()
          ->first();

        $this->cliente=AlpClientes::where('id_user_client', $compra->id_cliente)->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('noresponder@alpinago.com')
        ->subject($this->asunto)
        ->markdown('emails.compra-sac');
    }
}
