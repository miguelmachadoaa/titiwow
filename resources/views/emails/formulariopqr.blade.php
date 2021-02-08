@component('mail::message')

 Mensaje Enviado por el formulario PQR:<br>

 Tipo: {{ $data['tipo_pqr'] }} 

 Datos enviados: <br>

 <b>Nombre:</b> {{ $data['nombre_pqr'] }} <br>
 <b>Apellido:</b> {{ $data['apellido_pqr'] }} <br>
 <b>Tipo de Documento:</b> {{ $data['tdocume_pqr'] }} <br>
 <b>Identificación:</b> {{ $data['identificacion_pqr'] }} <br>
 <b>Email:</b> {{ $data['email_pqr'] }} <br>
 <b>Celular:</b> {{ $data['celular_pqr'] }} <br>
 <b>Pais:</b> {{ $data['pais_pqr'] }} <br>
 <b>Ciudad:</b> {{ $data['ciudad_pqr'] }} <br><br>

 <b>Mensaje:</b> {{ $data['mensaje_pqr'] }} <br><br>

 <b>Términos:</b> {{ $data['habeas_cliente'] }} <br>


{{ config('app.name') }}

@endcomponent
