<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\AlpConfiguracion;

class CronNomina extends Mailable
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
    public function __construct($enlace, $fecha, $documentos)
    {
        //
        $this->enlace=$enlace;
        $this->fecha=$fecha;    
        $this->documentos=$documentos;  
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
$email= $this->from($this->configuracion->correo_respuesta)
        ->subject('Ventas con Descuento Nomina ALPINA GO '.$this->fecha.' | AlpinaGo')
        ->markdown('emails.nomina');

        foreach ($this->documentos as $doc) {
           $email->attach($doc);
        }

        return $email;
    }
}
