<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompraSac extends Mailable
{
    use Queueable, SerializesModels;


    public $compra;
    public $detalles;
    public $fecha_entrega;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($compra, $detalles, $fecha_entrega)
    {
        //
        $this->compra=$compra;
        $this->detalles=$detalles;
        $this->fecha_entrega=$fecha_entrega;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.compra');
    }
}