@if($categoria->id)



  @if($categoria->destacado=='1')

        <button title="Destacado" data-url="{{ url('categorias/destacado') }}" data-destacado="0" data-id="{{ $categoria->id  }}"   class="btn btn-xs btn-link destacado">  <span class="glyphicon glyphicon-star" aria-hidden="true"></span>   </button>


    @else

        <button title="Normal" data-url="{{ url('categorias/destacado') }}" data-destacado="1" data-id="{{ $categoria->id  }}"   class="btn btn-xs btn-link destacado">  <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>   </button>

    @endif

@endif