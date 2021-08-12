<?php

namespace App\Mail;
use App\Models\AlpConfiguracion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AmigoRegistrado extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $lastname;
  public $configuracion;
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
        ->subject('Status de tus Amigos en Alpina Go | Alpina Por un mundo delicioso')
        ->markdown('emails.amigo-registrado');
    }
}
