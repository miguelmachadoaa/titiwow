@if(isset($orden))

    <button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->cod_oracle_pedido }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-info confirmar" > {{ $orden->estatus_nombre }} </button>

@endif