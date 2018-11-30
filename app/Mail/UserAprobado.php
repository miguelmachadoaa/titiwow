<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAprobado extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname)
    {
        //
        $this->name=$name;
        $this->lastname=$lastname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noresponder@alpinago.com')
        ->subject('Ya Puedes Comprar en Alpina Go | Alpina Alimenta tu vida')
        ->markdown('emails.aprobado');
    }
}
