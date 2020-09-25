<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\AlpConfiguracion;

class Contact extends Mailable
{
    use Queueable, SerializesModels;
    public $data;public $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        
$this->configuracion= AlpConfiguracion::where('id', '1')->first();
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->view('emails.contact');
        
return $this->from($this->configuracion->correo_respuesta)
            ->subject('New Contact from'.$this->data['contact-name'])
            ->markdown('emails.emailTemplates.contact');
    }
}
