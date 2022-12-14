@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Envios
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Envios</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Envios</a></li>
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
                       Envios
                    </h4>
                    <div class="pull-right">
                  <!--  <a href="{{ route('admin.envios.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear categoria</a>-->
                    </div>
                </div>
                <br />
                <div class="panel-body">

                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />


                    @if ($envios->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="tbOrdenes">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Orden</th>
                                    <th>Cliente</th>
                                    <th>Direccion</th>
                                    <th>Fecha Entrega</th>
                                    <th>Forma de Envio</th>
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
 <div class="modal fade" id="estatusEnviosModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('envios/confirmar')}}" id="estatusEnviosForm" name="estatusEnviosForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="envio_id" id="envio_id" value="">

                            {{ csrf_field() }}
                            <div class="row">


                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Estatus Envios
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="id_status" name="id_status" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            @foreach($estatus_envios as $est)
                                            <option value="{{ $est->id }}"
                                                    @if($est->id == old('id_status')) selected="selected" @endif >{{ $est->estatus_envio_nombre}}</option>
                                            @endforeach
                                          
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
                        <button type="button" class="btn  btn-primary sendEstatus" >Agregar</button>
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



$("#estatusEnviosForm").bootstrapValidator({
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


 $('#tbOrdenes').on('click','.updateStatus', function(){

        $('#envio_id').val($(this).data('id'));

        $('#id_status').val($(this).data('estatus'));

            $("#estatusEnviosModal").modal('show');
        });


 $('.sendEstatus').click(function () {
    
    var $validator = $('#estatusEnviosForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        envio_id=$('#envio_id').val();
        id_status=$('#id_status').val();
        notas=$('#notas').val();


        $.ajax({
            type: "POST",
            data:{  envio_id, id_status, notas },
            url: base+"/admin/envios/updatestatus",
                
            complete: function(datos){     

                $(".estatus_"+envio_id+'').html(datos.responseText);

                $('#estatusEnviosModal').modal('hide');
                
                $('#envio_id').val('');
                $('#id_status').val('');
                $('#notas').val('');
        
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});

    

        $(document).ready(function() {


            base=$('#base').val();
                
            var table =$('#tbOrdenes').DataTable( {
                "processing": true,
                "ajax": {
                    "url": base+'/admin/envios/data'
                }
            } );

            table.on( 'draw', function () {
                    $('.livicon').each(function(){
                        $(this).updateLivicon();
                    });
                } );


        } );

       </script>


@stop
