<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionAfiliado extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $token;
    public $empresa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $token, $empresa)
    {
        //
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
        return $this->markdown('emails.afiliado');
    }
}
