@if($producto->id)



  @if($producto->destacado=='1')

        <button data-url="{{ url('productos/destacado') }}" data-destacado="0" data-id="{{ $producto->id  }}"   class="btn btn-xs btn-danger destacado">  Destacado   </button>


    @else

        <button data-url="{{ url('productos/destacado') }}" data-destacado="1" data-id="{{ $producto->id  }}"   class="btn btn-xs btn-primary destacado">  Normal   </button>

    @endif

@endif