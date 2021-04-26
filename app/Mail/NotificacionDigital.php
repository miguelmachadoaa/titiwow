<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionDigital extends Mailable
{
    use Queueable, SerializesModels;


    public $producto;
    public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($producto)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->producto=$producto;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Compra Producto Digital | Alpina Alimenta tu vida')
        ->markdown('emails.notificacion-digital');

    }
}
