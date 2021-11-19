

@include('emails.header')

 # Hello {{ $data['contact-name'] }}

Welcome to SiteNameHere! We have received your details.<br />
The provided details are:<br />
**Name :** {{ $data['contact-name'] }}<br />
**Email :** {{ $data['contact-email'] }}<br />
**Message :** {{ $data['contact-msg'] }}

Thank you for Contacting SiteNameHere! We will revert you shortly.

Best regards,

Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
