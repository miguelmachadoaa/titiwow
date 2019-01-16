<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CronNuevosUsuarios extends Mailable
{
    use Queueable, SerializesModels;


    public $enlace;
    public $fecha;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enlace, $fecha)
    {
        //
        $this->enlace=$enlace;
        $this->fecha=$fecha;    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noresponder@alpinago.com')
        ->subject('Reporte de Nuevos Usuarios por Activar | ApinaGo')
        ->markdown('emails.nuevosusuarios');
    }
}
