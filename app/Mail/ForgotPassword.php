<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $configuracion;/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
            ->subject('Reestablecer Contraseña')
            ->markdown('emails.emailTemplates.forgot');
    }
}
