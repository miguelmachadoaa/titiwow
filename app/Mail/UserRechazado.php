<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;
class UserRechazado extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
    public $motivo;
public $configuracion;
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
        ->subject('Usuario Rechazado | Masterfile')
        ->markdown('emails.rechazado');
    }
}
