@if(isset($orden))

	@if($orden->ordencompra=='')

	 <button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->ordencompra }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-info aprobar" > Aprobar </button>

	@else

	<button data-id="{{ $orden->id }}"  data-codigo="{{ $orden->ordencompra }}"  data-estatus="{{ $orden->estatus }}" class="btn btn-xs btn-success aprobar" > Aprobado </button>

	@endif

   

@endif