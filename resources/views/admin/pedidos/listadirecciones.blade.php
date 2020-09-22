@if(isset($direcciones))

    @foreach($direcciones as $d)

    <option value="{{$d->id}}">{{ $d->nombre_estructura.' '.$d->principal_address.' - '.$d->secundaria_address.' '.$d->edificio_address.' '.$d->detalle_address.' '.$d->barrio_address }}</option>

    @endforeach

@endif