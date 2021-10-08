<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionMensaje extends Mailable
{
    use Queueable, SerializesModels;


    public $mensaje;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mensaje)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->mensaje=$mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Notificación | Alpina Por un mundo delicioso')
        ->markdown('emails.mensaje');
    }
}
