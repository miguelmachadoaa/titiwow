
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mis Direcciones 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{ url('clientes') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{ url('misdirecciones') }}">Mi Dirección </a>
                </li>
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
        <div class="welcome">
            <h3>Mi Dirección</h3>
        </div>
        <hr>

    <div class="row">
        <br>

        <!--<div class="col-sm-12" style="text-align: right;">

                    <button type="button" class="btn btn-raised btn-primary md-trigger addDireccionModal" data-toggle="modal" data-target="#modal-21">Agregar Nueva Direccion </button>

        </div>-->

    </div>

    <div class="row">

          <input type="hidden" name="base" id="base" value="{{ url('/') }}">

    <div class="col-sm-12 direcciones">   

        
        <br>    

            @if (isset($direcciones->id))


                  <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direcciones->id }}" >  

                <div class="col-sm-10 col-sm-offset-1">
                         <div class="alert alert-warning">Al actualizar su dirección su usuario quedara desactivado temporalmente. </div>

                    @else

                        <div class="alert alert-danger">Debe Esperar 24 horas para editar la dirección</div>

                    @endif
                </div>

                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-horizontal">

                                    <dt>Ubicación</dt>
                                    <dd>{{ $direcciones->country_name.', '.$direcciones->state_name.', '.$direcciones->city_name }}</dd>


                                    <dt>Dirección</dt>
                                    <dd>
                                       {{ $direcciones->nombre_estructura.' '.$direcciones->principal_address.' - '.$direcciones->secundaria_address.' '.$direcciones->edificio_address.' '.$direcciones->detalle_address.' '.$direcciones->barrio_address }}
                                    </dd>

                                    <dt>Notas</dt>
                                    <dd>{{ $direcciones->notas }}</dd>
                                    
                                </dl>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>
                </div>

                <div class="col-sm-10 col-sm-offset-1">
                    
                    @if ($editar==1)

                        <button 
                        data-address_id="{{ $direcciones->id }}"
                        data-state_id="{{ $direcciones->state_id }}"
                        data-city_id="{{ $direcciones->city_id }}"
                        data-estructura_id="{{ $direcciones->estructura_id }}"
                        data-principal_address="{{ $direcciones->principal_address }}"
                        data-secundaria_address="{{ $direcciones->secundaria_address }}"
                        data-edificio_address="{{ $direcciones->edificio_address }}"
                        data-detalle_address="{{ $direcciones->detalle_address }}"
                        data-barrio_address="{{ $direcciones->barrio_address }}"
                        data-notas="{{ $direcciones->notas }}"

                         class="btn btn-primary editAddress ">Editar Dirección</button>
                </div>
            @else
                <div class="alert alert-danger">
                        <p>El Cliente aún no posee direcciones Registradas</p>
                    </div>
            @endif   
             
      

        </div>
    
    </div>
</div>
<div class="container">
    <div class="form-group">
        <div class="col-lg-offset-5 col-lg-10" style="margin-bottom:20px;">
            <a class="btn btn-danger" type="button" href="{{ url('clientes') }}">Regresar</a>
        </div>
    </div>
</div>

<!-- Modal Direccion -->
 <div class="modal fade" id="editDireccionModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Editar Direccion</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            
                            <div class="col-sm-12">
                                

                           
                        
                        <form method="POST" action="{{url('cart/storedir')}}" id="editDireccionForm" name="editDireccionForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                            <input type="hidden" name="address_id" id="address_id" value="">

                            {{ csrf_field() }}

                            <div class="row">


                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">
                                <div class="" >
                                    <select id="state_id" name="state_id" class="form-control">
                                        <option value="">Seleccione Departamento</option>     
                                        @foreach($states as $state)
                                        <option value="{{ $state->id }}">
                                                {{ $state->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">
                                <div class="" >
                                    <select id="city_id" name="city_id" class="form-control">
                                        <option value="">Seleccione Ciudad</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">
                                                {{ $city->city_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">
                                <div class="input-group">
                                    <div class="" >
                                    <select id="id_estructura_address" name="id_estructura_address" class="form-control">
                                        @foreach($estructura as $estru)
                                        <option value="{{ $estru->id }}">
                                        {{ $estru->nombre_estructura}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                    <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>

                                    <input type="text" id="principal_address" name="principal_address" class="form-control" value="{!! old('principal_address') !!}" >
                                    <span class="input-group-addon">#</span>
                                    <input type="text" id="secundaria_address" name="secundaria_address" class="form-control" value="{!! old('secundaria_address') !!}" >
                                    <span class="input-group-addon">-</span>
                                    <input type="text" id="edificio_address" name="edificio_address" class="form-control" value="{!! old('edificio_address') !!}" >

                                    <!-- insert this line -->
                                    <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                                </div>
                                {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('detalle_address', 'has-error') }}">
                                <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior"
                                       value="{!! old('detalle_address') !!}" >
                                {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('barrio_address', 'has-error') }}">
                                <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio"
                                       value="{!! old('barrio_address') !!}" >
                                {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">
                                <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                                {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}
                            </div>

                           
                            <div class="clearfix"></div>

                        </form>

                         </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendDireccion" >Actualizar</button>
                    </div>
                </div>
            </div>
        </div>




<!-- Modal Direccion -->
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
     <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.addtocart').on('click', function(e){

            e.preventDefault();

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

                if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }

            });

        });



$("#editDireccionForm").bootstrapValidator({
    fields: {
        principal_address: {
            validators: {
                notEmpty: {
                    message: 'Principal es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        secundaria_address: {
            validators: {
                notEmpty: {
                    message: 'Secundaria  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        }, 
        edificio_address: {
            validators: {
                notEmpty: {
                    message: 'Edificio  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
         detalle_address: {
            validators: {
                notEmpty: {
                    message: 'Edificio  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },

        barrio_address: {
            validators: {
                notEmpty: {
                    message: 'Edificio  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
        
        

        city_id: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        },

        id_estructura_address: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        }


    }
});


$('.editAddress').on('click', function(){

            $('#address_id').val($(this).data('address_id'));

            $('#state_id').val($(this).data('state_id'));

            $('#city_id').val($(this).data('city_id'));

            $('#id_estructura_address').val($(this).data('estructura_id'));

            $('#principal_address').val($(this).data('principal_address'));

            $('#secundaria_address').val($(this).data('secundaria_address'));

            $('#edificio_address').val($(this).data('edificio_address'));

            $('#detalle_address').val($(this).data('detalle_address'));

            $('#barrio_address').val($(this).data('barrio_address'));

            $('#notas').val($(this).data('notas'));


            $("#editDireccionModal").modal('show');

        });


$('.sendDireccion').click(function () {
    
    var $validator = $('#editDireccionForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        address_id=$("#address_id").val();
        city_id=$("#city_id").val();
        id_estructura_address=$("#id_estructura_address").val();
        principal_address=$("#principal_address").val();
        secundaria_address=$("#secundaria_address").val();
        edificio_address=$("#edificio_address").val();
        detalle_address=$("#detalle_address").val();
        barrio_address=$("#barrio_address").val();
        notas=$("#notas").val();

        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{address_id, city_id, id_estructura_address, principal_address, secundaria_address, edificio_address, detalle_address, barrio_address, notas},

            url: base+"/clientes/storedir",
                
            complete: function(datos){     

                $(".direcciones").html(datos.responseText);

                $('#editDireccionModal').modal('hide');

               $("#address_id").val('');
                $("#city_id").val('');
                $("#estructura_id").val('');
                $("#principal_address").val('');
                $("#secundaria_address").val('');
                $("#edificio_address").val('');
                $("#detalle_address").val('');
                $("#barrio_address").val('');
        
                $("#notas").val('');
        
            
            }
        });

    }

});
// });
$('#addDireccionForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });

 $('body').on('click', '.deldir', function(){

            $('#del_id').val($(this).data('id'));

            $("#delDireccionModal").modal('show');

        });



$('.delDireccion').click(function () {
    
        id=$("#del_id").val();
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{id},
            url: base+"/clientes/deldir",
                
            complete: function(datos){     

                $(".direcciones").html(datos.responseText);

                $('#delDireccionModal').modal('hide');

               $("#del_id").val('');
            
            }
        });

});
        
            $(document).ready(function(){
        //Inicio select región
                $('select[name="country_id"]').on('change', function() {
                    $('select[name="city_id"]').empty();
                var countryID = $(this).val();
                var base = $('#base').val();
                    if(countryID) {
                        $.ajax({
                            url: base+'/configuracion/states/'+countryID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="state_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="state_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="state_id"]').empty();
                    }
                });
            //fin select región

            //inicio select ciudad
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
            //fin select ciudad
        });
    </script>
   
@stop