@if(isset($orden))

	@if($orden->tracking=='')

    	<div style="display: inline-block;" class="tracking_{{ $orden->id }}">
    	<button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->tracking }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-info tracking" > Enviar </button></div>

   @else

    	<div style="display: inline-block;" class="tracking_{{ $orden->id }}">
    	<button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->tracking }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-success tracking" > Enviado </button></div>

   @endif

   

@endif