@if(isset($envio))

    <button data-id="{{ $envio->id }}"   data-estatus="{{ $envio->estatus }}" class="btn btn-xs btn-info updateStatus" > {{ $envio->estatus_envio_nombre }} </button>

@endif