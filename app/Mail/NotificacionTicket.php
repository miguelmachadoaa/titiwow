<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionTicket extends Mailable
{
    use Queueable, SerializesModels;


    public $ticket;
    public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->ticket=$ticket;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Nuevo Ticket  | Alpina Alimenta tu vida')
        ->markdown('emails.notificacion-ticket');

    }
}
