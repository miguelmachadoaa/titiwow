
@extends('layouts/default')

{{-- Page title --}}
@section('title')

    Mis Direcciones 
    
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">

    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <style>
    
    .elegida{
        
        border: 1px solid red;

    }

    .help-block {

        display: block;

        margin-top: 5px;

        margin-bottom: 10px;

        color: #f10e0e;
    }

        
</style>

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
                    <a href="{{ secure_url('clientes') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#188ac9" data-hc="#188ac9"></i>
                    <a href="{{ secure_url('misdirecciones') }}">Mi Dirección </a>
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

        <div class="col-sm-12" style="text-align: right;">

            <button type="button" class="btn btn-raised btn-primary md-trigger showAddAddress" data-toggle="modal" data-target="#modal-21">Agregar Nueva Dirección </button>

        </div>

    </div>

    <div class="row">

        <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

        <div class="row" id="addAddressForm" style="margin-top: 1em; @if(count($errors)) @else display: none; @endif ">

            <div class="col-sm-12" style="border: 1px solid rgba(0,0,0,0.1);  padding: 2em; margin: 0em -2em;">



                <h3 style="text-align: center;margin-bottom: 1em;">Agregar Dirección</h3>

                

                <form method="POST" action="{{secure_url('clientes/storedir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal     ">

                     {{ csrf_field() }}

                     <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('titulo', 'has-error') }}">


                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="{!! old('titulo') !!}" >

                        {!! $errors->first('titulo', '<span class="help-block">:message</span>') !!}
                    
                    </div>
                            

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">

                        <select id="state_id" name="state_id" class="form-control">
                            <option value="">Seleccione Departamento</option>   

                            @foreach($states as $state)

                                <option value="{{ $state->id }}"> {{ $state->state_name}} </option>

                            @endforeach

                        </select>

                        {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}

                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">

                            <select id="city_id" name="city_id" class="form-control">

                                <option value="">Seleccione Ciudad</option>

                                @foreach($cities as $city)

                                    <option value="{{ $city->id }}">{{ $city->city_name}}</option>

                                @endforeach

                            </select>

                        {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}

                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                        <div class="col-sm-6" >
                            
                            <select id="id_estructura_address" name="id_estructura_address" class="form-control">

                                @foreach($estructura as $estru)

                                <option value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                @endforeach

                            </select>

                             {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div class="col-sm-6">
                            
                            <input type="text" id="principal_address" name="principal_address" class="form-control" placeholder="Ejemplo: 44 " value="{!! old('principal_address') !!}" >

                            {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                        </div>
                                    
                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                        <div class="col-sm-6" >

                            <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address') !!}" >

                            {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div class="col-sm-6">

                            <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address') !!}" >

                            {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('detalle_address', 'has-error') }}">

                        <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address') !!}" >

                        {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('barrio_address', 'has-error') }}">

                        <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address') !!}" >

                        {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                        {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                    </div>

                           
                    <div class="clearfix"></div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                        <button class="btn btn-primary" type="submit" >Crear </button>

                    </div>

                </form>

            </div>
        
        </div>


        <div class="col-sm-12 direcciones">   
        
            <br>    

                @if (isset($direccion->id))


                    <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direccion->id }}" >  

                    @if($configuracion->editar_direccion==1)

                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="alert alert-warning">Al actualizar su dirección su usuario quedara desactivado temporalmente. </div>

                        </div>

                    @endif

                @else

                    <div class="alert alert-danger">Debe Esperar 24 horas para editar la dirección</div>

                @endif

                
                @foreach($direcciones as $dir)

                    <div class="col-sm-6 col-md-4 col-lg-4 ">
                                            
                        <div class="panel panel-default">
                
                            <div class="panel-body @if($dir->default_address) {{  'elegida' }} @endif">
                                
                                <div class="box-body">

                                    <dl class="dl-horizontal">

                                        <dt>Nombre Dirección</dt>

                                        <dd>{{ $dir->titulo}}</dd>

                                        <dt>Ubicación</dt>
                                        
                                        <dd>{{ $dir->country_name.', '.$dir->state_name.', '.$dir->city_name }}</dd>

                                        <dt>Dirección</dt>

                                        <dd>{{ $dir->nombre_estructura.' '.$dir->principal_address.' - '.$dir->secundaria_address.' '.$dir->edificio_address.' '.$dir->detalle_address.' '.$dir->barrio_address }}
                                        </dd>

                                        <dt>Notas</dt>

                                        <dd>{{ $dir->notas }}</dd>

                                    </dl>

                                </div>
                                    <!-- /.box-body -->
                                <hr>

                                <div class="row">

                                    <div class="col-sm-12">

                                        @if($configuracion->edificio_address==0)

                                            <button
                                            data-id="{{ $dir->id }}"
                                            data-titulo="{{ $dir->titulo }}"
                                            data-state_id="{{ $dir->state_id }}"
                                            data-city_id="{{ $dir->city_id }}"
                                            data-estructura_id="{{ $dir->estructura_id }}"
                                            data-principal_address="{{ $dir->principal_address }}"
                                            data-secundaria_address="{{ $dir->secundaria_address }}"
                                            data-edificio_address="{{ $dir->edificio_address }}"
                                            data-detalle_address="{{ $dir->detalle_address }}"
                                            data-barrio_address="{{ $dir->barrio_address }}"
                                            data-notas="{{ $dir->notas }}" 
                                                 class="btn btn-primary btn-xs editAddress">Editar</button>

                                        @endif
                                            

                                        @if($dir->default_address)

                                        @else

                                            <a href="{{ secure_url('/clientes/setdir/'.$dir->id) }}" class="btn btn-xs btn-primary">Favorita</a>

                                        @endif

                                        <a href="{{ secure_url('/clientes/deldir/'.$dir->id) }}" class="btn btn-xs btn-danger">Eliminar</a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>  

                    @if($loop->iteration%3==0)

                        <div class="clearfix"></div>

                    @endif


                @endforeach

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

<!-- Modal Direccion -->
<div class="modal fade" id="editDireccionModal" role="dialog" aria-labelledby="modalLabeldanger">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header bg-primary">

                <h4 class="modal-title" id="modalLabeldanger">Editar Dirección</h4>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-sm-12">

                        <form method="POST" action="{{secure_url('cart/storedir')}}" id="editDireccionForm" name="editDireccionForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            <input type="hidden" name="edit_address_id" id="edit_address_id" value="">

                            {{ csrf_field() }}

                            <div class="row">

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('titulo', 'has-error') }}">

                                    <input type="text" class="form-control" id="edit_titulo" name="edit_titulo" placeholder="Nombre para esta dirección" value="{!! old('edit_titulo') !!}">
                                        
                                    {!! $errors->first('titulo', '<span class="help-block">:message</span>') !!}

                                </div>


                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">

                                    <select id="edit_state_id" name="edit_state_id" class="form-control">
                                        <option value="">Seleccione Departamento</option>     
                                        @foreach($states as $state)
                                        <option value="{{ $state->id }}">
                                                {{ $state->state_name}}</option>
                                        @endforeach
                                    </select>

                                    {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
                                </div>

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">

                                    <select id="edit_city_id" name="edit_city_id" class="form-control">

                                        <option value="">Seleccione Ciudad</option>

                                        @foreach($cities as $city)

                                            <option value="{{ $city->id }}">{{ $city->city_name}}</option>

                                        @endforeach

                                    </select>

                                    {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}

                                </div>

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                                    <div class="col-sm-6" >

                                        <select id="edit_id_estructura_address" name="edit_id_estructura_address" class="form-control">

                                            @foreach($estructura as $estru)

                                                <option value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-sm-6">
                                    
                                        <input type="text" id="edit_principal_address" name="edit_principal_address" class="form-control" value="{!! old('principal_address') !!}" >

                                    </div>

                                </div>

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                                    <div class="col-sm-6" >
                                        
                                        <input type="text" id="edit_secundaria_address" name="edit_secundaria_address" class="form-control" value="{!! old('secundaria_address') !!}" >


                                    </div>

                                    <div class="col-sm-6">

                                        <input type="text" id="edit_edificio_address" name="edit_edificio_address" class="form-control" value="{!! old('edificio_address') !!}" >

                                    </div>

                                </div>

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('detalle_address', 'has-error') }}">

                                    <input type="text" class="form-control" id="edit_detalle_address" name="edit_detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address') !!}" >

                                    {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                                </div>

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('barrio_address', 'has-error') }}">

                                    <input type="text" class="form-control" id="edit_barrio_address" name="edit_barrio_address" placeholder="Barrio" value="{!! old('barrio_address') !!}" >

                                    {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}
                                </div>

                                <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                                    <textarea style="margin: 4px 0;" id="edit_notas" name="edit_notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                                    {!! $errors->first('notas', '<span class="help-block">:message</span>')!!}

                                </div>

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

    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script>

    $('.showAddAddress').on('click', function(){

        if($('#addAddressForm').hasClass('open')){

            $('#addAddressForm').removeClass('open');

            $('#addAddressForm').fadeOut();

        }else{

            $('#addAddressForm').addClass('open');

            $('#addAddressForm').fadeIn();
        }
            
        $("#addDireccionModal").modal('show');

    });

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
        edit_principal_address: {
            validators: {
                notEmpty: {
                    message: 'Principal es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        edit_titulo: {
            validators: {
                notEmpty: {
                    message: 'Nombre de la dirección es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        
        edit_secundaria_address: {
            validators: {
                notEmpty: {
                    message: 'Calle Secundaria  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        }, 
        edit_edificio_address: {
            validators: {
                notEmpty: {
                    message: 'Edificio  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },

        edit_barrio_address: {
            validators: {
                notEmpty: {
                    message: 'Barrio de la dirección  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
        
        

        edit_city_id: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        },

        edit_id_estructura_address: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una estructura'
                }
            }
        }


    }
});
  

$("#addAddr222essForm").bootstrapValidator({
     fields: {
        titulo: {
            validators: {
                notEmpty: {
                    message: 'Nombre de Dirección es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        principal_address: {
            validators: {
                notEmpty: {
                    message: 'Calle Principal  es Requerido'
                    
                }
            },
            required: true,
        },
        secundaria_address: {
            validators: {
                notEmpty: {
                    message: 'Calle Secundaria  es Requerido'
                    
                }
            },
            required: true,
        },

        edificio_address: {
            validators: {
                notEmpty: {
                    message: 'Edificio  es Requerido'
                    
                }
            },
            required: true,
        },
        
        barrio_address: {
            validators: {
                notEmpty: {
                    message: 'El Barrio no puede esta vacio'
                }
            }
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
                    message: 'Debe seleccionar una Estructura '
                }
            }
        }
    }
});





$('.editAddress').on('click', function(){

    $('#edit_address_id').val($(this).data('id'));

    $('#edit_titulo').val($(this).data('titulo'));

    $('#edit_state_id').val($(this).data('state_id'));

    $('#edit_city_id').val($(this).data('city_id'));

    $('#edit_id_estructura_address').val($(this).data('estructura_id'));

    $('#edit_principal_address').val($(this).data('principal_address'));

    $('#edit_secundaria_address').val($(this).data('secundaria_address'));

    $('#edit_edificio_address').val($(this).data('edificio_address'));

    $('#edit_detalle_address').val($(this).data('detalle_address'));

    $('#edit_barrio_address').val($(this).data('barrio_address'));

    $('#edit_notas').val($(this).data('notas'));

    $("#editDireccionModal").modal('show');

});


$('.sendDireccion').click(function () {
    
    var $validator = $('#editDireccionForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        _token=$("input[name='_token']").val();
        address_id=$("#edit_address_id").val();
        titulo=$("#edit_titulo").val();
        city_id=$("#edit_city_id").val();
        id_estructura_address=$("#edit_id_estructura_address").val();
        principal_address=$("#edit_principal_address").val();
        secundaria_address=$("#edit_secundaria_address").val();
        edificio_address=$("#edit_edificio_address").val();
        detalle_address=$("#edit_detalle_address").val();
        barrio_address=$("#edit_barrio_address").val();
        notas=$("#edit_notas").val();

        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{_token, titulo, address_id, city_id, id_estructura_address, principal_address, secundaria_address, edificio_address, detalle_address, barrio_address, notas},

            url: base+"/clientes/updatedir",
                
            complete: function(datos){    


                location.reload();

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