@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ordenes
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ordenes Recibidas</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ordenes</a></li>
        <li class="active">Index</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Ordenes Recibidas
                    </h4>
                    <div class="pull-right">
                  <!--  <a href="{{ route('admin.ordenes.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear categoria</a>-->
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($ordenes->count() >= 1)
                        <div class="table-responsive">

                         <table class="table table-bordered" id="tbOrdenes">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Referencia</th>
                                    <th>Cliente</th>
                                    <th>Forma de Envio</th>
                                    <th>Forma de Pago</th>
                                    <th>Referencia</th>
                                    <th>Total</th>
                                    <th>Codigo Oracle</th>
                                    <th>Factura</th>
                                    <th>Tracking</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ordenes as $row)
                                <tr id="{!! $row->id !!}">
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->referencia!!}</td>
                                    <td>{!! $row->first_name.' '.$row->last_name !!}</td>
                                    <td>{!! $row->nombre_forma_envios !!}</td>
                                    <td>{!! $row->nombre_forma_pago !!}</td>
                                    <td> 
                                        
                                        @if($row->json!=Null)
                                            {{ json_decode($row->json)->merchant_order_id }}

                                        @endif

                                         </td>
                                    <td>{!! number_format($row->monto_total,2) !!}</td>

                                    <td>{!! $row->ordencompra!!}</td>
                                    <td>{!! $row->factura!!}</td>
                                    <td>{!! $row->tracking!!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>

                                            <a href="{{ route('admin.ordenes.detalle', $row->id) }}">
                                                <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Detalle"></i>
                                            </a>

                                           

                                            
                                         @if($row->ordencompra=='')

                                            <div style="display: inline-block;" class="aprobar_{{ $row->id }}">
                                            <button data-id="{{ $row->id }}"  data-codigo="{{ $row->ordencompra }}"  data-estatus="{{ $row->estatus }}" class="btn btn-xs btn-info aprobar" > Aprobar </button></div>

                                           @else

                                            <div style="display: inline-block;" class="aprobar_{{ $row->id }}">
                                            <button data-id="{{ $row->id }}"  data-codigo="{{ $row->ordencompra }}"  data-estatus="{{ $row->estatus }}" class="btn btn-xs btn-success aprobar" > Aprobado </button></div>


                                           @endif

                                            



                                            <!--<div style="display: inline-block;" class="pago_{{ $row->id }}">  

                                            <button data-id="{{ $row->id }}" class="btn btn-xs btn-success pago" > {{ $row->estatus_pago_nombre }} </button></div>-->


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        No se encontraron registros
                    @endif   
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>



<!-- Modal Detalle -->


  
<!-- Modal Direccion -->
 <div class="modal fade" id="confirmarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                            <input type="hidden" name="confirm_id" id="confirm_id" value="">

                            {{ csrf_field() }}
                            <div class="row">

                                

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Estatus Ordenes
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="id_status" name="id_status" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            @foreach($estatus_ordenes as $est)
                                            <option value="{{ $est->id }}"
                                                    @if($est->id == old('id_status')) selected="selected" @endif >{{ $est->estatus_nombre}}</option>
                                            @endforeach
                                          
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Oracle Pedido </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="cod_oracle_pedido" name="cod_oracle_pedido" type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                               

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Notas</label>

                                    <div class="col-sm-8">
                                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control"></textarea>
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendConfirmar" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->



  
<!-- Modal Direccion -->
 <div class="modal fade" id="aprobarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Aprobar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('ordenes/confirmar')}}" id="aprobarOrdenForm" name="aprobarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                            <input type="hidden" name="aprobar_id" id="aprobar_id" value="">

                            {{ csrf_field() }}
                            <div class="row">


                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Aprobacion Pedido </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="cod_aprobar_pedido" name="cod_aprobar_pedido" type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                               

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Notas</label>

                                    <div class="col-sm-8">
                                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control"></textarea>
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendAprobar" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->







@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')


<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>

 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

  <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script>



$("#confirmarOrdenForm").bootstrapValidator({
    fields: {
        cod_oracle_pedido: {
            validators: {
                notEmpty: {
                    message: 'Codigo Oracle  es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        id_status: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar un estatus'
                }
            }
        }
    }
});

$("#aprobarOrdenForm").bootstrapValidator({
    fields: {
        cod_aprobar_pedido: {
            validators: {
                notEmpty: {
                    message: 'Codigo Aprobacion  es Requerido'
                }
            },
            required: true,
            minlength: 3
        }
    }
});


 $('#tbOrdenes').on('click','.confirmar', function(){

        $('#confirm_id').val($(this).data('id'));

        $('#cod_oracle_pedido').val($(this).data('codigo'));

        $('#id_status').val($(this).data('estatus'));

            $("#confirmarOrdenModal").modal('show');
        });

 $('#tbOrdenes').on('click','.aprobar', function(){

   
        $('#aprobar_id').val($(this).data('id'));

        $('#cod_aprobar_pedido').val($(this).data('codigo'));


            $("#aprobarOrdenModal").modal('show');
        });


 $('.sendConfirmar').click(function () {
    
    var $validator = $('#confirmarOrdenForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        confirm_id=$('#confirm_id').val();
        id_status=$('#id_status').val();
        cod_oracle_pedido=$('#cod_oracle_pedido').val();
        notas=$('#notas').val();


        $.ajax({
            type: "POST",
            data:{ base, confirm_id, id_status, cod_oracle_pedido, notas },
            url: base+"/admin/ordenes/storeconfirm",
                
            complete: function(datos){     

                $(".estatus_"+confirm_id+'').html(datos.responseText);

                $('#confirmarOrdenModal').modal('hide');

                
                $('#confirm_id').val('');
                $('#id_status').val('');
                $('#cod_oracle_pedido').val('');
                $('#notas').val('');
        
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});



 $('.sendAprobar').click(function () {
    
    var $validator = $('#aprobarOrdenForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        id=$('#aprobar_id').val();
        codigo=$('#cod_aprobar_pedido').val();
        notas=$('#notas').val();


        $.ajax({
            type: "POST",
            data:{ base, id, codigo,  notas },
            url: base+"/admin/ordenes/aprobar",
                
            complete: function(datos){     

                //$(".aprobar_"+id+'').html(datos.responseText);
                $("#"+id+'').remove();

                $('#aprobarOrdenModal').modal('hide');
                
                $('#aprobar_id').val('');
                $('#cod_aprobar_pedido').val('');
                $('#notas').val('');
        
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});



        $('#tbOrdenes').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#tbOrdenes').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

       </script>


@stop

