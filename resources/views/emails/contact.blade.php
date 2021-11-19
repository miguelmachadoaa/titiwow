

@include('emails.header')


We have received a new contact mail.<br />
**Name :** {{ $data['contact-name'] }}<br />
**Email :** {{ $data['contact-email'] }}<br />
**Message :** {{ $data['contact-msg'] }}


Thanks,

Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
