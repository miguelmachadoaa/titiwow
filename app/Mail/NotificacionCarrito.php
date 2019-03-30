<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionCarrito extends Mailable
{
    use Queueable, SerializesModels;


    public $compra;
    public $detalles;
    public $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($compra, $detalles, $configuracion)
    {
        //
        $this->compra=$compra;
        $this->detalles=$detalles;
        $this->configuracion=$configuracion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noresponder@alpinago.com')
        ->subject('Finaliza Tu Compra | Alpina Alimenta tu vida')
        ->markdown('emails.carrito');
    }
}
