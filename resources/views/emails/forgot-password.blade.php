@include('emails.header')

    {{-- Body --}}

    # Hello  {!! $user['user_name'] !!},<br>

    Please click on the following link to updated your password


<p style="text-aling:center">
    <a  href="{{ $user['forgotPasswordUrl']}}" class="button button-blue " target="_blank">Reset Password</a>
</p>



Gracias,<br>
{{ config('app.name') }}
@include('emails.footer')
