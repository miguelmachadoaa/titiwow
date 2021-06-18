<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AlpConfiguracion;

class PQR extends Mailable
{
    use Queueable, SerializesModels;


    public $data;
     public $configuracion;
     public $archivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$archivo)
    {
        //
        $this->data=$data;
        $this->archivo=$archivo;
        $this->configuracion = AlpConfiguracion::where('id', '1')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

       $email= $this->from($this->configuracion->correo_respuesta)
        ->subject('PQR | Alpina Alimenta tu vida')
        ->markdown('emails.formulariopqr');

        if ($this->archivo!='0') {
             $email->attach('/home2/alpago/public_html/uploads/pqr/'.$this->archivo, [ 'as' => $this->archivo ]);
        }

        return $email;

    }
}
