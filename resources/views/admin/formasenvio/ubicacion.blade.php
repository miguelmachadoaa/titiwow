@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ciudades de envio
@parent
@stop

@section('header_styles')
    <link href="{{ secure_asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
@stop



{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ciudades de Envio</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ciudades de envio</a></li>
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
                       Ciudades de Envio 
                    </h4>
                    <div class="pull-right">
                   
                      <button type="button" class="btn btn-default addCiudad"><i class="fa fa-plus"></i>Crear</button>
                    
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    <div class="ciudades">  
                    @if ($ciudades->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Ciudad</th>
                                    <th>Dias para entrega</th>
                                    <th>Hora limite recepcion</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ciudades as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->state_name.' - '.$row->city_name!!}</td>
                                    <td>{!! $row->dias !!}</td>
                                    <td>{!! $row->hora !!}</td>
                                    <td>
                                            
                                            

                                        <button data-id="{{ $row->id }}" type="button" class=" btn btn-danger delciudad"><i class="fa fa-trash"></i></button>

                                            <!-- let's not delete 'Admin' group by accident -->
                                              

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
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')


  <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

  <script src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ secure_asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ secure_asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>





<!-- Modal Direccion -->
 <div class="modal fade" id="addCiudadModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Ciudad</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('formasenvio/storeciudad')}}" id="addCiuadadForm" name="addCiuadadForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                            <input type="hidden" name="id_forma" id="id_forma" value="{{ $formas->id }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Departamento
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="state_id" name="state_id" class="form-control ">
                                            <option value="">Seleccione</option>

                                                @foreach($states as $state)

                                                    <option value="{{ $state->id }}">
                                                        {{ $state->state_name}}</option>
                                                @endforeach
                                                        
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Ciudad
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="city_id" name="city_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            
                                          
                                        </select>
                                    </div>
                                </div>



                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Dias para la entrega </label>

                                    <div class="col-sm-8">
                                        <input  style="margin: 4px 0;" id="dias" name="dias" type="number" step="1" min="1"  placeholder="Dias para la entrega" class="form-control">
                                    </div>
                                </div>
                                

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Hora Maxima de recepcion de ordenes </label>

                                    <div class="col-sm-8">
                                     <input style="margin: 4px 0;" id="hora" name="hora" type="text" placeholder="Hora maxima de recepcion de ordenes" class="form-control">
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendCiudad" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->

      <!-- Modal Direccion -->
 <div class="modal fade" id="delCiudadModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Ciudad</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="del_id" id="del_id">

                        <h3>Esta seguro que desar eliminar esta direccion</h3>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-danger delCiudad" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->




<script>

    $("#hora").datetimepicker({
    format: 'HH:mm'
}).parent().css("position :relative");

    $('body').on('click', '.delciudad', function(){

            $('#del_id').val($(this).data('id'));

            $("#delCiudadModal").modal('show');

        });



$('.delCiudad').click(function () {
    
        id=$("#del_id").val();
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{id},
            url: base+"/admin/formasenvio/delcity",
                
            complete: function(datos){     

                $(".ciudades").html(datos.responseText);

                $('#delCiudadModal').modal('hide');

               $("#del_id").val('');
            
            }
        });

});


    
    $('.addCiudad').on('click', function(){
            $("#addCiudadModal").modal('show');
        });


    $("#addCiuadadForm").bootstrapValidator({
    fields: {
        
        dias: {
            validators: {
                notEmpty: {
                    message: 'Dias  es Requerido'
                    
                }
            }
        },
        
        hora: {
            validators: {
                notEmpty: {
                    message: 'Hora no puede esta vacion'
                }
            }
        },

        city_id: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        }
    }
});


    $('.sendCiudad').click(function () {
    
    var $validator = $('#addCiuadadForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        id_forma=$("#id_forma").val();
        city_id=$("#city_id").val();
        dias=$("#dias").val();
        hora=$("#hora").val();

             
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{ city_id, dias, hora, id_forma},
            url: base+"/admin/formasenvio/storecity",
                
            complete: function(datos){     

                $(".ciudades").html(datos.responseText);

                $('#addCiudadModal').modal('hide');

                $("#city_id").val('');

                $("#hora").val('');

                $("#dias").val('');
            
            }
        });

    }

});



 $('select[name="state_id"]').on('change', function() {
                    var stateID = $(this).val();

            
                    

                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });








    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>




@stop
