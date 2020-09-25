<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\AlpConfiguracion;

class CronNuevosUsuarios extends Mailable
{
    use Queueable, SerializesModels;


    public $enlace;
    public $fecha;

public $configuracion;

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
        
$this->configuracion= AlpConfiguracion::where('id', '1')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
return $this->from($this->configuracion->correo_respuesta)
        ->subject('Reporte de Nuevos Usuarios por Activar | ApinaGo')
        ->markdown('emails.nuevosusuarios');
    }
}
