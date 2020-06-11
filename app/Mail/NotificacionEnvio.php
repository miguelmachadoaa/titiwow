<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionEnvio extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $orden;
    public $envio;
    public $status;
    public $input;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $orden, $envio, $status, $input)
    {
        //
        $this->user=$user;
        $this->orden=$orden;
        $this->envio=$envio;
        $this->status=$status;
        $this->input=$input;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('noresponder@alpinago.com')
        ->subject('El envio de su orden '.$this->orden->id.' ha sido '.$this->status->estatus_envio_nombre.' | Alpina Alimenta tu vida')
        ->markdown('emails.envio');

    }
}
