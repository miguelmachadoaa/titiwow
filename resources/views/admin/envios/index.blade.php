@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Envios
@parent
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

                                @foreach ($envios as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->id_orden!!}</td>
                                    <td>{!! $row->first_name.' '.$row->last_name !!}</td>
                                    <td>{!! $row->state_name.' '.$row->city_name!!}</td>
                                    <td>{!! $row->fecha_envio!!}</td>
                                    <td>{!! $row->nombre_forma_envios !!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>

                                            <a class="btn btn-xs btn-default" href="{{ route('admin.envios.detalle', $row->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <div style="display: inline-block;" class="estatus_{{ $row->id }}">
                                            
                                                <button data-id="{{ $row->id }}"  data-estatus="{{ $row->estatus }}" class="btn btn-xs btn-info updateStatus" > {{ $row->estatus_envio_nombre }} </button>

                                            </div>


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
 <div class="modal fade" id="estatusEnviosModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('envios/confirmar')}}" id="estatusEnviosForm" name="estatusEnviosForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
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


<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>

 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

  <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

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
