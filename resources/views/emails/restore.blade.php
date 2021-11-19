@include('emails.header')

    {{-- Body --}}
    # Hello  {!! $user['user_name'] !!},<br>

Welcome to SiteNameHere! Please click on the following link to Restore Your account:<br />


<p style="text-aling:center">
    <a  href="{{ $user['activationUrl'] }}" class="button button-blue " target="_blank">Restore Account </a>
</p>


    Thanks,
    @include('emails.footer')