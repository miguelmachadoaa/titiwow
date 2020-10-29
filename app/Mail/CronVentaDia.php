<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class CronVentaDia extends Mailable
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
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
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
        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Reporte de Ventas del Dia | AlpinaGo')
        ->markdown('emails.ventasdeldia');
    }
}
