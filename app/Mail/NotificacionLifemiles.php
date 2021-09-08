<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionLifemiles extends Mailable
{
    use Queueable, SerializesModels;


    public $lifemile;
    public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lifemile)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->lifemile=$lifemile;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Notificación Lifemile | Alpina Por un mundo delicioso')
        ->markdown('emails.notificacion-lifemiles');

    }
}
