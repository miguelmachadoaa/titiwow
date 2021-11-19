@include('emails.header')

{{$mensaje}}


Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
