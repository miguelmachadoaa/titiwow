<?php

namespace App\Mail;

use App\Models\AlpEnvios;



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

            $asunto= ' ENVIO EXPRESS | Nuevo Pedido Nro.: '.$this->compra->id.'';
        }

        $this->asunto=$asunto;
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
