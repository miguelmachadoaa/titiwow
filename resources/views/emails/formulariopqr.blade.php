@include('emails.header')

 <p>Mensaje Enviado por el formulario PQR:</p><br>

 <p>Tipo: {{ $data['tipo_pqr'] }} </p>

 <p>Datos enviados: </p><br>
<p>
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

</p>
 {{ $archivo }}

{{ config('app.name') }}

@include('emails.footer')
