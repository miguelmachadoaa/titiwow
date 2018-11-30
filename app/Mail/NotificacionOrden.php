<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionOrden extends Mailable
{
    use Queueable, SerializesModels;


    public $orden;
    public $texto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orden, $texto)
    {
        //
        $this->orden=$orden;
        $this->texto=$texto;    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noresponder@alpinago.com')
        ->subject('Nuevo Pedido | CEDI')
        ->markdown('emails.notificacion-orden');
    }
}
