<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CronNomina extends Mailable
{
    use Queueable, SerializesModels;


    public $enlace;
    public $fecha;

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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email= $this->from('noresponder@alpinago.com')
        ->subject('Ventas con Descuento Nomina ALPINA GO '.$this->fecha.' | ApinaGo')
        ->markdown('emails.nomina');

        foreach ($this->documentos as $doc) {
           $email->attach($doc);
        }

        return $email;
    }
}
