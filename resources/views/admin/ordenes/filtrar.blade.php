@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Buscar Ordenes
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Buscar Ordenes</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Buscar Ordenes</a></li>
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
                       Ordenes
                    </h4>
                    <div class="pull-right">
                  <!--  <a href="{{ route('admin.ordenes.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear categoria</a>-->
                    </div>
                </div>
                <br />

             
                <div class="panel-body">


                    <div class="row">
                        <div class="col-sm-12">


                          
                            <form class="form-horizontal bf" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/ordenes/filtrar/list') }}">

                                {{ csrf_field() }}

                               <div class="form-group {{ $errors->
                                first('buscar', 'has-error') }}">
                                    <label for="title" class="col-sm-2 control-label">
                                        Buscar
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Buscar" @if(isset($buscar)) value="{{$buscar}}" @endif>
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('buscar', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-4">
                                       
                                        <button type="submit" class="btn btn-success">
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                          </form>
                            
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-sm-12">


                    @if ($ordenes->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="tbOrdenes">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Referencia</th>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th>Total</th>
                                    <th>Cupón</th>
                                    <th>Almacen</th>
                                    <th>Ciudad</th>
                                    <th>Origen</th>
                                    <th>Creado</th>
                                    <th>Estado Pago</th>
                                    <th>Estado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($ordenes as $o)

                                <tr>
                                    <td>{{$o->id}}</td>
                                    <td>{{$o->referencia}}</td>
                                    <td>{{$o->first_name.' '.$o->last_name}}</td>
                                    <td>{{$o->email}}</td>
                                    <td>{{$o->monto_total}}</td>
                                    <td>{{$o->codigo_cupon}}</td>
                                    <td>

                                        @if(isset($almacenes[$o->id_almacen]))
                                        {{$almacenes[$o->id_almacen]}}
                                        @endif 
                                    </td>

                                    <td>
                                        @if(isset($direcciones[$o->id_address]))

                                            @if(isset($ciudades[$direcciones[$o->id_address]]))

                                            {{$ciudades[$direcciones[$o->id_address]]}}

                                            @endif


                                        @endif
                                        {{$o->city_name}}
                                    </td>
                                    
                                    <td>@if ($o->origen=='1') 
                                    {{'Tomapedidos'}}             
                                     @else
                                    {{'web'}}

                                    @endif</td>

                                    <td>{{$o->created_at}}</td>
                                    <td><div style='display: inline-block;' class='pago_{{$o->id}}'>  <button data-id='{{$o->id}}' class='btn btn-xs btn-success pago' > {{$estatus_pago[$o->estatus_pago]}} </button></div></td>
                                    <td><span class='badge badge-default' >{{$estatus_ordenes[$o->estatus]}}</span></td>
                                    <td>

                                        <a class='btn btn-primary btn-xs' href='{{route('admin.ordenes.detalle', $o->id)}}'  target='_blank'> ver detalles </a> <div style='display: inline-block;' class='estatus_{{$o->id}}'>


                                            @if ($o->estatus!=4)
                    
                                            <button data-id='{{$o->id}}'  data-codigo='{{$o->cod_oracle_pedido}}'  data-estatus='{{$o->estatus}}' class='btn btn-xs btn-danger confirmar' > Cancelar </button></div>

                                          @endif

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
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
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
                                            
                                            <option value="4"
                                                     selected="selected" >Cancelado</option>
                                          
                                        </select>
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
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="aprobarOrdenForm" name="aprobarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
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


<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>

 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">

<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

  <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script>



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



   



$(document).ready(function() {

    base=$('#base').val();

    
  /*  var table =$('#tbOrdenes').DataTable({
        "processing": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": base+'/admin/ordenes/data'
        }
    });

    table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    });*/


    $('#tbOrdenes').DataTable();

        
   /* var table =$('#tbOrdenes').DataTable({
        "serverSide": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": base+'/admin/ordenes/data'
        }
    });
    

    table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    });*/




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

                $('.estatus_'+confirm_id).html(datos); 

                table.ajax.reload();



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

               table.ajax.reload();

                $('#aprobarOrdenModal').modal('hide');
                
                $('#aprobar_id').val('');
                $('#cod_aprobar_pedido').val('');
                $('#notas').val('');
        
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});





});




       </script>


@stop
