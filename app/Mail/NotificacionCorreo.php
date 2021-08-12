<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionCorreo extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->user=$user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Confirmación de Correo  | Alpina Por un mundo delicioso')
        ->markdown('emails.notificacion-correo');
    }
}
