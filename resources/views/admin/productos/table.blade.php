<table class="table table-responsive table-striped table-bordered" id="alpProductos-table" width="100%">
    <thead>
     <tr>
        <th>ID</th>
        <th>Nombre de Producto</th>
        <th>Referencia</th>
        <th>Imagen</th>
        <th>Categoria Principal</th>
        <th>Inventario</th>
        <th>Estado</th>
        <th >Action</th>
     </tr>
    </thead>
    <tbody>
    @foreach($productos as $alpProductos)
        <tr>
            <td>{!! $alpProductos->id !!}</td>
            <td>{!! $alpProductos->nombre_producto !!}</td>
            <td>{!! $alpProductos->referencia_producto !!}</td>
            <td>{!! $alpProductos->imagen_producto !!}</td>
            <td>{!! $alpProductos->id_categoria_default !!}</td>
            <td>{!! $alpProductos->id !!}</td>
            <td>{!! $alpProductos->estado_registro !!}</td>
            <td>
                 <a href="{{ route('admin.alpProductos.show', collect($alpProductos)->first() ) }}">
                     <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view alpProductos"></i>
                 </a>
                 <a href="{{ route('admin.alpProductos.edit', collect($alpProductos)->first() ) }}">
                     <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit alpProductos"></i>
                 </a>
                 <a href="{{ route('admin.alpProductos.confirm-delete', collect($alpProductos)->first() ) }}" data-toggle="modal" data-target="#delete_confirm">
                     <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete alpProductos"></i>
                 </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@section('footer_scripts')

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <script>$(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});</script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>
        $('#alpProductos-table').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#alpProductos-table').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

       </script>

@stop