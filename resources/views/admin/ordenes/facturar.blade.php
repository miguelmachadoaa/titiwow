@if(isset($orden))

	@if($orden->factura=='')

    <div style="display: inline-block;" class="facturar_{{ $orden->id }}">
    <button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->factura }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-info aprobar" > Facturar </button></div>

   @else

    <div style="display: inline-block;" class="facturar_{{ $orden->id }}">
    <button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->ordencompra }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-success aprobar" > Facturado </button></div>


   @endif

   

@endif