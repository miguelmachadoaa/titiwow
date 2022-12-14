@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ordenes Compramas
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ordenes Compramas</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ordenes Compramas</a></li>
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
                    @if ($ordenes->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="tbOrdenes">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Referencia</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Forma de Envio</th>
                                    <th>Forma de Pago</th>
                                    <th>Total</th>
                                    <th>Codigo Oracle</th>
                                    <th>Cupón</th>
                                    <th>Factura</th>
                                    <th>Tracking</th>
                                    <th>Almacen</th>
                                    <th>Ciudad</th>
                                    <th>Origen</th>
                                    <th>Creado</th>
                                    <th>Estado Compramas</th>
                                    <th>Estado Pago</th>
                                    <th>Estado Orden </th>
                                    <th>Estado Proceso Compramas</th>
                                    <th>Reenviar</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

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
        
    var table =$('#tbOrdenes').DataTable({
        "processing": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": base+'/admin/ordenes/datacompramas'
        }
    });

    table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    });






$('.compramas').on('click', function(){

    alert('click');
 
        base=$('#base').val();
        
        id=$(this).data(id);

        alert(id);
       

       /* $.ajax({
            type: "POST",
            data:{ base, id },
            url: base+"/admin/ordenes/"+id+"/sendcompramas",
                
            complete: function(datos){     

                table.ajax.reload();

            
            }
        });*/


        //document.getElementById("addDireccionForm").submit();


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
