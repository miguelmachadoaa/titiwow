<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionAfiliado extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $token;
    public $empresa;
public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $token, $empresa)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->name=$name;
        $this->lastname=$lastname;
        $this->token=$token;
        $this->empresa=$empresa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Invitación a Alpina Go Corporativo | Alpina Alimenta tu vida')
        ->markdown('emails.afiliado');
    }
}
