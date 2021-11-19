@include('emails.header')


¡Hola {{$data['cliente']->first_name.' '.$data['cliente']->last_name}}!

Queremos comunicarte que por problemas técnicos el pedido {{$data['orden']->referencia}} que realizaste no pudo procesarse de manera correcta.

Ya hemos realizado la devolución de tu dinero a traves de nuestra plataforma de pagos. Te deberá llegar un correo electrónico con la confirmación de dicha devolución.

Lamentamos cualquier molestia o inconveniente que esto haya podido generar. Ya hemos solucionado el problema, por lo que puedes ingresar nuevamente a nuestro portal para realizar una nueva compra. 

¡Gracias por tu atención!


{{ config('app.name') }}
@include('emails.footer')
