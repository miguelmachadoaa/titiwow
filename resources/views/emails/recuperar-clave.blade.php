@component('mail::message')

Hola! {{ $data['user_name'] }}

Se ha solicitado un enlace para cambiar su contraseña. Puede hacerlo a través del siguiente boton

@component('mail::button', ['url' => $data['forgotPasswordUrl']])

Restablecer Contraseña 

@endcomponent

Si no ha solicitado esto, ignore este correo electrónico. Su contraseña no cambiará hasta que acceda al enlace anterior y cree una nueva.



Gracias,<br>
{{ config('app.name') }}
@endcomponent
