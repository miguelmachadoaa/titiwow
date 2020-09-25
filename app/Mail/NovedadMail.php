<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;
class NovedadMail extends Mailable
{
    use Queueable, SerializesModels;


    public $data;
public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data=$data;
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
        ->subject('Novedad con tu pedido: '.$this->data['orden']->referencia.' | Alpina GO!')
        ->markdown('emails.novedad');
    }
}
