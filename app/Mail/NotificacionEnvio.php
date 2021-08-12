<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class NotificacionEnvio extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $orden;
    public $envio;
    public $status;
    public $input;
public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $orden, $envio, $status, $input)
    {
        $this->configuracion= AlpConfiguracion::where('id', '1')->first();//
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

        return $this->from($this->configuracion->correo_respuesta)
        ->subject('El envio de su orden '.$this->orden->id.' ha sido '.$this->status->estatus_envio_nombre.' | Alpina Por un mundo delicioso')
        ->markdown('emails.envio');

    }
}
