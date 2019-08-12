           <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

<table class="table table-responsive table-striped table-bordered" id="alpProductos-table" width="100%">
    <thead>
     <tr>
        <th>ID</th>
        <th>Nombre de Producto</th>
        <th>Referencia</th>
        <th>SKU</th>
        <th>Imagen</th>
        <th>Categoria Principal</th>
        <th>Precio</th>
        <th>Estado</th>
        <th >Action</th>
     </tr>
    </thead>
    <tbody>
  
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


<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>



      $(document).ready(function() {

        base=$('#base').val();
        
    var table =$('#alpProductos-table').DataTable( {
        "processing": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": base+'/admin/productos/data'
        }
    } );

    table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );


   	$('#alpProductos-table').on('click', '.desactivar', function(){
            var id=$(this).data('id');
            var desactivar=$(this).data('desactivar');
            var url=$(this).data('url');
            $.post(url, {id, desactivar}, function(data) {
                    table.ajax.reload();
            });

        });


     $('#alpProductos-table').on('click', '.destacado', function(){


            var id=$(this).data('id');
            var destacado=$(this).data('destacado');
            var url=$(this).data('url');


            $.post(url, {id, destacado}, function(data) {

            	//alert(data);

                   // $('#td_destacado_'+id).html(data);
                     table.ajax.reload();

            });
           
        });


     $('#alpProductos-table').on('click', '.sugerencia', function(){


            var id=$(this).data('id');
            var sugerencia=$(this).data('sugerencia');
            var url=$(this).data('url');

            $.post(url, {id, sugerencia}, function(data) {

                    //$('#td_sugerencia_'+id).html(data);
                    table.ajax.reload();

            });
           
        });


} );


       </script>

@stop