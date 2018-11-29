<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro Empresas| Alpina Go!</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ secure_asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ secure_asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{secure_asset('assets/vendors/iCheck/css/all.css')}}" />
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/register.css') }}">
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <!--end of page level css-->
</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <a href="{{ secure_url('/') }}"><img src="{{ secure_asset('assets/img/login.png') }}" alt="Alpina GO!"></a>
            <h3 class="text-primary">Registro de Afiliados Empresas </h3>
            <!-- Notifications -->
            <div id="notific">
            @include('notifications')
            </div>
            <form action="{{ secure_url('signupafiliado') }}" method="POST" id="reg_form">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="back" id="back" value="0">
                <input type="hidden" name="empresa" id="empresa" value="{{ $id }}">

                <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nombre"
                           value="{{ $amigo->nombre_amigo }}" >
                    {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Apellido"
                           value="{{ $amigo->apellido_amigo }}" >
                    {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                </div>


                <div class="form-group {{ $errors->first('id_type_doc', 'has-error') }}">
                    <div class="" >
                        <select id="id_type_doc" name="id_type_doc" class="form-control">
                            <option value="">Seleccione Tipo de Documento</option>     
                            @foreach($t_documento as $tdoc)
                            <option value="{{ $tdoc->id }}">
                            {{ $tdoc->abrev_tipo_documento}} - {{ $tdoc->nombre_tipo_documento}}</option>
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first('id_type_doc', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('doc_cliente', 'has-error') }}">
                    <input type="text" class="form-control" id="doc_cliente" name="doc_cliente" placeholder="Nro de Documento"
                           value="{!! old('doc_cliente') !!}" >
                    {!! $errors->first('doc_cliente', '<span class="help-block">:message</span>') !!}
                </div>
 <div class="form-group {{ $errors->first('telefono_cliente', 'has-error') }}">
                    <input type="number" class="form-control" id="telefono_cliente" name="telefono_cliente" placeholder="Teléfono"
                           value="{!! old('telefono_cliente') !!}" >
                    {!! $errors->first('telefono_cliente', '<span class="help-block">:message</span>') !!}
                </div>



                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <input type="email" readonly="true" class="form-control" id="email" name="email" placeholder="Email"
                           value="{{ $amigo->email_amigo }}" >
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                           placeholder="Confirmar Contraseña">
                    {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                <hr />
                <h4 class="text-primary">Dirección</h4>
                <hr />
                <div class="form-group {{ $errors->first('cod_alpinista', 'has-error') }}">
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
                <div class="form-group {{ $errors->first('city_id', 'has-error') }}">
                    <div class="" >
                        <select id="city_id" name="city_id" class="form-control">
                            <option value="">Seleccione Ciudad</option>
                        </select>
                    </div>
                    {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                <div class="form-group {{ $errors->first('calle_address', 'has-error') }}">
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

                        <input type="text" id="principal_address" name="principal_address" class="form-control" value="{!! old('principal_address') !!}" placeholder="Principal">
                        <span class="input-group-addon hidden-xs azul">#</span>
                        <input type="text" id="secundaria_address" name="secundaria_address" class="form-control" value="{!! old('secundaria_address') !!}" placeholder="#">
                        <span class="input-group-addon hidden-xs azul">-</span>
                        <input type="text" id="edificio_address" name="edificio_address" class="form-control" value="{!! old('edificio_address') !!}" placeholder="-">

                        <!-- insert this line -->
                        <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                    </div>
                    {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('detalle_address', 'has-error') }}">
                    <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior"
                           value="{!! old('detalle_address') !!}" >
                    {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('barrio_address', 'has-error') }}">
                    <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio"
                           value="{!! old('barrio_address') !!}" >
                    {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="clearfix"></div>
                
               <div class="clearfix"></div>
                <div class="checkbox form-group">
                    <label style="font-size:12px;">
                        <input type="checkbox" name="habeas_cliente" value="1">  Acepto los <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go</a> y la  <a href="{{ secure_asset('uploads/files/política_de_tratamiento_de_la_informacion.PDF') }}" class="menu-item" target="_blank" title="Políticas de Tratamiento de la Información" alt="Políticas de Tratamiento de la Información">Políticas de Tratamiento de la Información</a> de ALPINA PRODUCTOS ALIMENTICIOS S.A.
                    </label>
                </div>

                 <div class="clearfix"></div>
                 <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_KEY')}}"  style="padding:10px 30px !important"></div>
                    {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-block btn-primary">Registrarse</button>
                        </div>
                        <div class="col-sm-6">
                            <a class="btn btn-block btn-danger" href="{{ secure_url('/') }}">Regresar a Inicio</a>
                        </div>
                    </div>
                </div>
                <br />
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            Si ya tiene cuenta, por favor Inicie Sesión <a href="{{ secure_url('login') }}"> Ingresar</a>
                        </div>
                    </div>
                </div>
            </form>


            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
            
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/register_custom.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

<!--global js end-->


<script >
$(document).ready(function(){
        $("#state_id").select2();
        $("#city_id").select2();
        $("#id_type_doc").select2();
        $('#cod_alpinista').hide();
        // For oncheck callback
        $('#chkalpinista').on('ifChecked', function () { $('#cod_alpinista').show();})

        // For onUncheck callback
        $('#chkalpinista').on('ifUnchecked', function () { $('#cod_alpinista').hide();})

   
        //Inicio select región
                        
            //inicio select ciudad
            $('select[name="state_id"]').on('change', function() {
                var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/registro/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key+'">'+ value +'</option>');
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


</body>
</html>