<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionTomapedidos extends Mailable
{
    use Queueable, SerializesModels;


    public $orden;
    public $texto;
public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orden, $texto)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
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
        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Nuevo Pedido | ApinaGo')
        ->markdown('emails.notificacion-tomapedidos');
    }
}
