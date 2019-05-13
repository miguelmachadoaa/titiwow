<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $mensaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $mensaje)
    {
        //
        $this->name=$name;
        $this->lastname=$lastname;
        $this->mensaje=$mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('noresponder@alpinago.com')
        ->subject('Bienvenido a Alpina Go! | Alpina Alimenta tu vida')
        ->markdown('emails.welcome');

    }
}
