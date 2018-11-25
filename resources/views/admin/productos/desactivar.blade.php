@if($producto->id)



  @if($producto->estado_registro=='1')

        <button type="button" data-url="{{ secure_url('productos/desactivar') }}" data-desactivar="2" data-id="{{ $producto->id  }}" class="btn btn-responsive button-alignment btn-success btn_sizes desactivar" style="font-size: 12px !important;" >Activo</button>

    @else

        <button type="button" data-url="{{ secure_url('productos/desactivar') }}" data-desactivar="1" data-id="{{ $producto->id  }}" class="btn btn-responsive button-alignment btn-danger btn_sizes desactivar" style="font-size: 12px !important;">Inactivo</button>
            
    @endif

@endif

