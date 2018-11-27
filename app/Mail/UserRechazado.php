<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRechazado extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $motivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $motivo)
    {
        //
        $this->name=$name;
        $this->lastname=$lastname;
        $this->motivo=$motivo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.rechazado');
    }
}
