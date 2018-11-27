@component('mail::message')

<!--<p style="text-align: center;"><img src="{{ secure_url('assets/img/login.png') }}"></p>-->

 <h3><b>Tu amigo  {{ $name.' '.$lastname }}</b></h3>

<p>Se ha registrado exitosamente, sigue enviando invitaciones y podrás ganar muchas más comisiones y premios </p>



@component('mail::button', ['url' => secure_url('/')])
Visita Nuesta pagina 
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
