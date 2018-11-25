<table class="table table-responsive table-striped table-bordered" id="alpProductos-table" width="100%">
    <thead>
     <tr>
        <th>ID</th>
        <th>Nombre de Producto</th>
        <th>Referencia</th>
        <th>Imagen</th>
        <th>Categoria Principal</th>
        <th>Precio</th>
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
            <td><img src="../uploads/productos/{!! $alpProductos->imagen_producto !!}" height="60px"></td>
            <td>{!! $alpProductos->nombre_categoria !!}</td>
            <td>{!! number_format($alpProductos->precio_base,2) !!}</td>
            <td id="acti_{{ $alpProductos->id }}">
                @if($alpProductos->estado_registro == 1)
                    <button type="button" data-url="{{ secure_url('productos/desactivar') }}" data-desactivar="2" data-id="{{ $alpProductos->id  }}" class="btn btn-responsive button-alignment btn-success btn_sizes desactivar" style="font-size: 12px !important;" >Activo</button>
                @elseif($alpProductos->estado_registro == 2)
                    <button type="button" data-url="{{ secure_url('productos/desactivar') }}" data-desactivar="1" data-id="{{ $alpProductos->id  }}" class="btn btn-responsive button-alignment btn-danger btn_sizes desactivar" style="font-size: 12px !important;">Inactivo</button>
                @endif
            </td>
            <td>
                 <a href="{{ route('admin.productos.show', collect($alpProductos)->first() ) }}">
                     <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view alpProductos"></i>
                 </a>
                 <a href="{{ route('admin.productos.edit', collect($alpProductos)->first() ) }}">
                     <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit alpProductos"></i>
                 </a>

                  <div style=" display: inline-block; padding: 0; margin: 0;" id="td_{{ $alpProductos->id }}">
                
                    @if($alpProductos->destacado=='1')

                        <button title="Destacado" data-url="{{ secure_url('productos/destacado') }}" data-destacado="0" data-id="{{ $alpProductos->id  }}"   class="btn btn-xs btn-link  destacado">  <span class="glyphicon glyphicon-star" aria-hidden="true"></span>   </button>


                    @else

                        <button title="Normal" data-url="{{ secure_url('productos/destacado') }}" data-destacado="1" data-id="{{ $alpProductos->id  }}"   class="btn btn-xs btn-link  destacado">  <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>   </button>

                    @endif

            </div>


                 <a href="{{ secure_url('admin/productos/'.$alpProductos->id.'/confirm-delete' ) }}" data-toggle="modal" data-target="#delete_confirm">
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


<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>



         $('#alpProductos-table').on('click', '.destacado', function(){


            var id=$(this).data('id');
            var destacado=$(this).data('destacado');
            var url=$(this).data('url');


            $.post(url, {id, destacado}, function(data) {

                    $('#td_'+id).html(data);

            });
           
        });


         $('#alpProductos-table').on('click', '.desactivar', function(){
            var id=$(this).data('id');
            var desactivar=$(this).data('desactivar');
            var url=$(this).data('url');
            $.post(url, {id, desactivar}, function(data) {
                    $('#acti_'+id).html(data);
            });

        });


         $(document).ready(function(){

            $('#alpProductos-table').DataTable({
                          responsive: true,
                          pageLength: 10,
                          order: [ 0, 'desc' ]
                      });
            
            $('#alpProductos-table').on( 'page.dt', function () {
                         setTimeout(function(){
                               $('.livicon').updateLivicon();
                         },500);
                      } );
         });





       </script>

@stop