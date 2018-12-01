<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionAmigo extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $token;
    public $embajador;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $token, $embajador)
    {
        //
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
        return $this->from('noresponder@alpinago.com')
        ->subject('Invitación a Alpina Go | Alpina Alimenta tu vida')
        ->markdown('emails.amigo');
    }
}
