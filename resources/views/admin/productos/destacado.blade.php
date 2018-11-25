@if($producto->id)



  @if($producto->destacado=='1')

        <button title="Destacado" data-url="{{ secure_url('productos/destacado') }}" data-destacado="0" data-id="{{ $producto->id  }}"   class="btn btn-xs btn-link destacado">  <span class="glyphicon glyphicon-star" aria-hidden="true"></span>   </button>


    @else

        <button title="Normal" data-url="{{ secure_url('productos/destacado') }}" data-destacado="1" data-id="{{ $producto->id  }}"   class="btn btn-xs btn-link destacado">  <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>   </button>

    @endif

@endif