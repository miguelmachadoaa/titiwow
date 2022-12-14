@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ordenes Enviadas
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ordenes Enviadas</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ordenes Enviadas</a></li>
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
                       Ordenes Enviadas
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
                                    <th>Almacen</th>
                                    <th>Ciudad</th>
                                    <th>Origen</th>
                                    <th>Total</th>
                                    <th>Codigo Oracle</th>
                                    <th>Cupón</th>
                                    <th>Factura</th>
                                    <th>Tracking</th>
                                    <th>Creado</th>
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
 <div class="modal fade" id="enviarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Enviar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="enviarOrdenForm" name="enviarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="enviar_id" id="enviar_id" value="">

                            {{ csrf_field() }}
                            <div class="row">


                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Tracking </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="tracking" name="tracking" type="text" placeholder="" class="form-control">
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
                        <button type="button" class="btn  btn-primary sendEnviar" >Agregar</button>
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



$("#enviarOrdenForm").bootstrapValidator({
    fields: {
        tracking: {
            validators: {
                notEmpty: {
                    message: 'Codigo Oracle  es Requerido'
                }
            },
            required: true,
            minlength: 3
        }
    }
});






 $('#tbOrdenes').on('click','.tracking', function(){

   
        $('#enviar_id').val($(this).data('id'));

        $('#tracking').val($(this).data('codigo'));


            $("#enviarOrdenModal").modal('show');
        });





       
         $(document).ready(function() {


        base=$('#base').val();
        
    var table =$('#tbOrdenes').DataTable( {
        "processing": true,
        "ajax": {
            "url": base+'/admin/ordenes/dataenviados'
        }
    } );

    table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );




 $('.sendEnviar').click(function () {
    
    var $validator = $('#enviarOrdenForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        id=$('#enviar_id').val();
        codigo=$('#tracking').val();
        notas=$('#notas').val();


        $.ajax({
            type: "POST",
            data:{ base, id, codigo,  notas },
            url: base+"/admin/ordenes/enviar",
                
            complete: function(datos){     

                table.ajax.reload();

                $('#enviarOrdenModal').modal('hide');
                
                $('#enviar_id').val('');
                $('#tracking').val('');
                $('#notas').val('');
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});



} );

       </script>


@stop

