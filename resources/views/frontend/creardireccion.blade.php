
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

 <meta property="og:title" content="{{ $configuracion->seo_titulo }} | Alpina GO!">

    <meta property="og:description" content="{{ $configuracion->seo_descripcion }}">

    <meta property="og:image" content="{{ $configuracion->seo_image }}" />

    @if(isset($url))

    <link rel="canonical" href="{{$url}}" />


    <meta property="og:url" content="{{$url}}" />

    @endif


    <meta name="description" content="{{$configuracion->seo_description}}"/>



    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="#">Carrito de Compras</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center" id="cartshow">

    <div data-cart="{{json_encode($cart)}}"></div>

    <div class="row" id="table_detalle">

    <h3>Agregar Dirección de Envio</h3>   

    <form method="POST" action="{{secure_url('clientes/storedir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal     ">

        <input type="hidden" id="paso" name="paso" value="2" >
    
             {{ csrf_field() }}

        <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('titulo', 'has-error') }}">

        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="{!! old('titulo') !!}" >

        {!! $errors->first('titulo', '<span class="help-block">:message</span>') !!}
    
    </div>

    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">

        <select id="state_id" name="state_id" class="form-control select2 js-example-responsive">
            <option value="">Seleccione Departamento</option>   

            @foreach($states as $state)

                <option value="{{ $state->id }}"> {{ $state->state_name}} </option>

            @endforeach

        </select>

        {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}

    </div>


    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">

            <select id="city_id" name="city_id" class="form-control select2 js-example-responsive">

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

    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

        <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address') !!}" >

        {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

    </div>

        <div style="margin-left: 8%;" class="form-group col-sm-10 col-sm-offset-1 id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
        <div class="" >
            <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio') !!}" class="form-control select2 js-example-responsive">
                <option value="">Seleccione Barrio</option>
            </select>
        </div>
        {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
    </div>



    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

        {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

    </div>

            
    <div class="clearfix"></div>

    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

        <button class="btn btn-primary" type="submit" >Crear Dirección y Continuar con la compra </button>

    </div>

</form>



    </div>

    <div class="row">
         
        <div class="col-sm-12">
             
            <div class="res"></div>

        </div>

    </div>










<div class="row">
    <div class="col-sm-12" data-json="{{json_encode($cart)}}">
        
    </div>
</div>

</div>

</div>




<div class="modal fade" id="CartModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MI PEDIDO</h4>
              </div>
              <div class="modal-body bodycarrito">
                
              @if(is_array($cart))

                @foreach($cart as $key=>$cr)

                <div class="col-xs-12 " >

                    <div class="row productoscarritodetalle"  style="padding:0; margin:0;     border-bottom: 2px solid rgba(0,0,0,0.1);">
                        
                        <div class="col-sm-2" style="padding-top: 3%;">
                            <img style="width:100% ; max-width: 90px;" src="{{secure_url('uploads/productos/'.$cr->imagen_producto)}}"  alt="{{$cr->nombre_producto}}">
                        </div>
                        <div class="col-sm-4" style="padding-top: 3%;">
                            <p>{{$cr->nombre_producto}}</p>
                        </div>
                        
                        <div class="col-sm-2 col-xs-4" style="padding-top: 3%;">
                            <p>{{number_format($cr->precio_oferta, 0, ',', '.')}} </p>
                        </div>

                        <div class="col-sm-1 col-xs-1" style="padding-top: 3%;">
                            <p>{{$cr->cantidad}} </p>
                        </div>


                        <div class="col-sm-2 col-xs-4" style="padding-top: 3%; ">
                            <p>{{number_format($cr->precio_oferta*$cr->cantidad, 0, ',', '.')}} </p>
                        </div>

                        <div class="col-sm-1 col-xs-2" style="padding-left:0; padding-right:0; padding-top: 3%;     text-align: right; ">
                            <a data-id="{{ $cr->slug}}" data-slug="{{ $cr->slug}}"  href="#0" class="delete-item">
                                <img style="width:32px; padding-right:0; margin-bottom: 10px;" src="{{secure_url('assets/images/borrar.png')}}" alt="">
                            </a>
                        </div>

                    </div>

                </div>

                @endforeach

                @endif
              </div>
            
            </div><!-- /.modal-content -->

        </div>
    </div>




@endsection

{{-- page level scripts --}}
@section('footer_scripts')

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>

        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('#addDireccionForm').keypress( function(event){
                if (event.which == '13') {
                    event.preventDefault();
                }
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




            $('select[name="city_id"]').on('change', function() {
            var stateID = $(this).val();
            var base = $('#base').val();

                if(stateID) {

                    $.ajax({
                        url: base+'/configuracion/barrios/'+stateID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {

                            
                            $('select[name="id_barrio"]').empty();

                            if (JSON.stringify(data).length>25) {

                                $('.barrio_address').addClass('hidden');
                                $('·barrio_address').val(' ');

                                $('.id_barrio').removeClass('hidden');

                            }else{

                                $('.barrio_address').removeClass('hidden');

                                $('#id_barrio').val(0);

                                $('.id_barrio').addClass('hidden');

                            }

                            $.each(data, function(key, value) {
                                $('select[name="id_barrio"]').append('<option value="'+ key+'">'+ value +'</option>');
                            });

                        }
                    });
                }else{

                    $('select[name="id_barrio"]').empty();

                }
            });
        
        });
       

        
    </script>
@stop