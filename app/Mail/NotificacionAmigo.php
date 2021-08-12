<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionAmigo extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $token;
    public $embajador;
public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $token, $embajador)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
        $this->name=$name;
        $this->lastname=$lastname;
        $this->token=$token;
        $this->embajador=$embajador;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->configuracion->correo_respuesta)
        ->subject('Invitación a Alpina Go | Alpina Por un mundo delicioso')
        ->markdown('emails.amigo');
    }
}
