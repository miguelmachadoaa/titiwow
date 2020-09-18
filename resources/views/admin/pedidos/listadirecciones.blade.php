@if(isset($direcciones))

    @foreach($direcciones as $d)

    <option value="{{$d->id}}"></option>

    @endforeach

@endif