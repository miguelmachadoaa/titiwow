
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mis Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{ secure_url('clientes/') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{ secure_url('miscompras/') }}">Compras </a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="welcome">
            <h3>Mis Compras</h3>
        </div>
        <hr>
    <div class="row">

        <div class="col-sm-12">
        @if(!$compras->isEmpty())

         <div class="table-responsive">

             <table class="table table-responsive width100"  id="table">
                    <tr>
                        <th>Id</th>
                        <th>Nombre Cliente</th>
                        <th>Forma Envio</th>
                        <th>Forma de Pago </th>
                        <th>Monto Total </th>
                        <th>Creado</th>
                        <th>Estado de Orden</th>
                        <th>Acciones</th>
                    </tr>


            @foreach($compras as $row)

                    <tr>
                        <td>
                            {{ $row->id }}
                        </td>
                        <td>
                            {{ $row->first_name.' '.$row->last_name }}
                        </td>
                        <td>
                            {{ $row->nombre_forma_envios }}
                        </td>
                        <td>
                             @if($row->json!=null)

                                @if(isset(json_decode($row->json)->response->payment_type_id))

                                    {{ $row->nombre_forma_pago.' '.json_decode($row->json)->response->payment_type_id }}


                                @endif 



                            @else

                                {{ $row->nombre_forma_pago}}

                            @endif 
                        </td>
                        <td>
                            {{ number_format($row->monto_total,0,",",".") }}
                        </td>
                        <td>
                            {{ $row->created_at }}
                        </td>

                        <td>
                            {{ $row->estatus_nombre }}
                        </td>

                        <td>    
                               

                            @if($row->id_forma_pago=='2')

                                @if(!isset($pagos[$row->id]))

                                    @if($row->json!=null)

                                            @if(isset(json_decode($row->json)->response->status))

                                                @if(json_decode($row->json)->response->status!='pending' && json_decode($row->json)->response->status!='aproved')

                                                    <a  class="btn  btn-xs btn-info" href="{{ secure_url('clientes/pagar/'.$row->id) }}">Pagar Orden</a>
                                                
                                                @endif 
                                            @endif 


                                    @endif 

                                @endif 
                            @endif 

                                  <button class="btn btn-info btn-xs seeDetalle" data-url="{{ secure_url('clientes/'.$row->id.'/detalle') }}" data-id="{{ $row->id }}" href="{{ secure_url('clientes/'.$row->id.'/detalle') }}">
                                    <i class="livicon "  data-name="eye" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Ver Detalle"></i>
                                 </button>


                                @if($row->estatus!='4')

                                    @if($row->estatus!='7')

                                
                                    <div style="display: inline-block;" class="estatus_{{ $row->id }}">

                                    <button data-id="{{ $row->id }}"  data-codigo="{{ $row->ordencompra }}"  data-estatus="{{ $row->estatus }}" class="btn btn-xs btn-danger confirmar" > Cancelar Orden  </button>

                                    </div>

                                    @endif

                                @endif
                                 
                                 <!-- let's not delete 'Admin' group by accident -->
                                            
                                          

                        </td>
                    </tr>
                
            @endforeach
             </table>

         </div>
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen Compras aún.
            </div>
        @endif
        </div>
        
    </div>

</div>
<div class="container">
    <div class="form-group">
        <div class="col-lg-offset-5 col-lg-10" style="margin-bottom:20px;">
            <a class="btn btn-danger" type="button" href="{{ secure_url('clientes') }}">Regresar</a>
        </div>
    </div>
</div>

<!-- Modal Detalle -->
 <div class="modal fade" id="detalleModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Detalle de Compra</h4>
                    </div>
                    <div class="modal-body">
                        <h3>Detalle de la Compra</h3>
                         <div class="" id="tbDetalle"> 

                        </div>
                        
                        


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->



<!-- Modal Direccion -->
 <div class="modal fade" id="confirmarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="confirm_id" id="confirm_id" value="">

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>¿Estas Seguro que quieres cancelar el pedido?</h3>
                                </div>
                                
                                

                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Volver</button>
                        <button type="button" class="btn  btn-primary sendConfirmar" >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->


<input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">



@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>




    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });



        $("#confirmarOrdenForm").bootstrapValidator({
    fields: {
        
        id_status: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar un estatus'
                }
            }
        }
    }
});




        $('body').on('click','.confirmar', function(){

        $('#confirm_id').val($(this).data('id'));

        $('#cod_oracle_pedido').val($(this).data('codigo'));

        $('#id_status').val($(this).data('estatus'));

            $("#confirmarOrdenModal").modal('show');
        });




        $('.sendConfirmar').click(function () {
    
    var $validator = $('#confirmarOrdenForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        confirm_id=$('#confirm_id').val();
        id_status=4;
        cod_oracle_pedido=$('#cod_oracle_pedido').val();
        notas='Cancelado por el cliente';


        $.ajax({
            type: "POST",
            data:{ base, confirm_id, id_status, cod_oracle_pedido, notas },
            url: base+"/admin/ordenes/storeconfirm",
                
            complete: function(datos){     

                $(".estatus_"+confirm_id+'').html(datos.responseText);

                $('#confirmarOrdenModal').modal('hide');
                
                $('#confirm_id').val('');
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});













        $('.seeDetalle').on('click', function(){

            $('#detalleModal').modal('show');

            id=$(this).data('id');

            url=$(this).data('url');

            $.get(url, {}, function(data) {

                $('#tbDetalle').html(data);

            });

        })


    </script>
@stop