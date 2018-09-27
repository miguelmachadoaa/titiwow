@if($categoria->id)



  @if($categoria->destacado=='1')

        <button data-url="{{ url('categorias/destacado') }}" data-destacado="0" data-id="{{ $categoria->id  }}"   class="btn btn-xs btn-danger destacado">  Destacado   </button>


    @else

        <button data-url="{{ url('categorias/destacado') }}" data-destacado="1" data-id="{{ $categoria->id  }}"   class="btn btn-xs btn-primary destacado">  Normal   </button>

    @endif

@endif