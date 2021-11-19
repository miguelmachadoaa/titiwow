
@include('emails.header')

# Hello  {!! $user['user_name'] !!},<br>

Welcome to AlpinaGo! Please click on the following link to confirm your SiteNameHere account:<br />



<p style="text-aling:center">
    <a  href="{{ $user['activationUrl'] }}" class="button button-blue " target="_blank">Visitar Página </a>
</p>



@include('emails.footer')