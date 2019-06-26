           <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

<table class="table table-responsive table-striped table-bordered" id="alpProductos-table" width="100%">
    <thead>
     <tr>
        <th>ID</th>
        <th>Nombre de Producto</th>
        <th>Referencia</th>
        <th>Ciudad</th>
        <th>Rol</th>
        <th>Precio</th>
        <th>Estatus</th>
     </tr>
    </thead>
    <tbody>

        @foreach($precio_grupo as $producto)
            <tr>
                
            <td>{{$producto->id_producto}}</td>
            <td>{{$producto->nombre_producto}}</td>
            <td>{{$producto->referencia_producto}}</td>
           
            

                <td>{{ $producto->city_name }}</td>
                <td>{{ $producto->name  }}</td>


              

            <td>

               @if($producto->operacion==1)

                    {{ $producto->precio_base}}
               @endif

               @if($producto->operacion==2)

                    {{ $producto->precio_base*(1-($producto->precio/100))}}
               @endif

               @if($producto->operacion==3)

                    {{ $producto->precio }}
               @endif

        </td>

            @if($producto->deleted_at==NULL)
            <td style="color: green; font-weight: 600;" >Activo</td>

            @else

            <td style="color: red; font-weight: 600;">Eliminado</td>

            @endif

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

    


    $(document).ready(function(){



        $('#alpProductos-table').dataTable();

       
        //Inicio select región
                

            //inicio select ciudad
            $('select[name="state"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="cities"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="cities"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="cities"]').empty();
                    }
                });
            //fin select ciudad
        });


       </script>

@stop