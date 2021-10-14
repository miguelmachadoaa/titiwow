@if(isset($lifemile->id))

    <div class="btn-activo{{$lifemile->id}}">
        @if($lifemile->estado_registro =='0')
            <button data-id="{{$lifemile->id}}"  class="desactivado btn btn-danger">
                Desactivado
            </button>
        @else
            <button data-id="{{$lifemile->id}}"  class="activado btn btn-primary">
                Activo
            </button>
        @endif 
    </div>

@endif