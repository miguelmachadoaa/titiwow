<?php

namespace App\Mail;

use App\Models\AlpEnvios;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompraRealizada extends Mailable
{
    use Queueable, SerializesModels;


    public $compra;
    public $detalles;
    public $fecha_entrega;
    public $envio;
    public $role;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($compra, $detalles, $fecha_entrega)
    {
        //
        $this->compra=$compra;
        $this->detalles=$detalles;
        $this->fecha_entrega=$fecha_entrega;
        $this->envio=AlpEnvios::where('id_orden', $compra->id)->first();

        $r=RoleUser::where('user_id', $compra->id_cliente)->first();

        $role=Roles::where('id', $r->role_id)->first();

        $this->role=$role;
        

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noresponder@alpinago.com')
        ->subject('Gracias por Su Compra | Alpina Alimenta tu vida')
        ->markdown('emails.compra');
    }
}
