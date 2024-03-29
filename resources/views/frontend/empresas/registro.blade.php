<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro Empresas| Alpina Go!</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ secure_asset('assets/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{ secure_asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">    <!--end of global css-->
    <!--page level css starts-->
    <!--link type="text/css" rel="stylesheet" href="{{secure_asset('assets/vendors/iCheck/css/all.css')}}" /-->
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/register.css') }}">
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <!--end of page level css-->
    @if (App::environment('production')) 

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129370910-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-129370910-1');
        </script>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PG9RTMH');</script>
        <!-- End Google Tag Manager -->
    @endif
</head>
<body>
@if (App::environment('production')) 
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PG9RTMH"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
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
                <div class="form-group">
                    <div style="padding: 0;" class="form-group  col-sm-6  col-xs-6 {{ $errors->first('id_estructura_address', 'has-error') }}">
                            <select id="id_estructura_address" name="id_estructura_address" class="form-control">
                                @foreach($estructura as $estru)
                                <option value="{{ $estru->id }}">
                                {{ $estru->abrevia_estructura}} - {{ $estru->nombre_estructura}} </option>
                                @endforeach
                            </select>
                        {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div style="padding-right: 0;" class="form-group col-sm-6 col-xs-6  {{ $errors->first('principal_address', 'has-error') }}">
                        <div class="input-group">
                            <!--span class="input-group-addon azul" id="basic-addon2">Principal</span-->
                            <input type="text" class="form-control" id="principal_address" name="principal_address" style="font-style:italic" value="{!! old('principal_address') !!}" placeholder="Ejemplo: 100" aria-describedby="basic-addon2">
                        </div>
                        {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div style="padding: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('secundaria_address', 'has-error') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" id="secundaria_address" name="secundaria_address" value="{!! old('secundaria_address') !!}" placeholder="Ejemplo: #21" aria-describedby="basic-addon3">
                    </div>
                    {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div style="padding-right: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('edificio_address', 'has-error') }}">
                    <div class="input-group">
                        <input type="text" value="{!! old('edificio_address') !!}" class="form-control" id="edificio_address" name="edificio_address" placeholder="Ejemplo: -14" aria-describedby="basic-addon4">
                    </div>
                    {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                <div class="form-group {{ $errors->first('detalle_address', 'has-error') }}">
                    <input type="text" value="{!! old('detalle_address') !!}"  class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior"
                           value="{!! old('detalle_address') !!}" >
                    {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('barrio_address', 'has-error') }}">
                    <input type="text" value="{!! old('barrio_address') !!}" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio"
                           value="{!! old('barrio_address') !!}" >
                    {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                <hr />

                <div class="form-group {{ $errors->first('habeas_cliente', 'has-error') }} checkbox">
                    <label style="font-size:12px;">
                        <input type="checkbox" name="habeas_cliente" value="1" require>  Acepto los <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go</a> y la  <a href="{{ secure_asset('uploads/files/politica_de_tratamiento_de_la_informacion.PDF') }}" class="menu-item" target="_blank" title="Políticas de Tratamiento de la Información" alt="Políticas de Tratamiento de la Información">Políticas de Tratamiento de la Información</a> de ALPINA PRODUCTOS ALIMENTICIOS S.A.
                    </label>
                    {!! $errors->first('habeas_cliente', '<span class="help-block">:message</span>') !!}
                </div>

                 <div class="clearfix"></div>
                 <div class="form-group">
                    <div class="g-" data-sitekey="{{env('CAPTCHA_KEY')}}"  style="padding:10px 30px !important"></div>
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
        <div>
            <div class="form-group">
                <div class="row text-center" style="background:rgba(0,0,0,0.4);color:#ffffff;padding:20px;margin-top:20px;">
                    <div class="col-md-12">
                        <div style="font-size:12px;padding:0px 20px;">
                            <p>He sido informado (a) por ALPINA PRODUCTOS ALIMENTICIOS S.A. (Responsable del tratamiento) de lo siguiente: (i) los datos suministrados en este documento serán tratados para los siguientes propósitos: enviar o utilizar la información para fines contractuales, de atención al cliente, de marketing (tales como análisis de consumos, trazabilidad de marca entre otros), comerciales, actualizar datos y brindar información relevante; (ii) es facultativo responder preguntas sobre datos sensibles o de menores de edad; (iii) como titular de los datos y/o representante del menor, tengo los derechos de conocer, actualizar, rectificar o suprimir mi información o revocar esta autorización; (iv) en caso de no ser resuelta mi solicitud directamente, y de manera subsidiaria, tengo derecho a presentar quejas ante la Superintendencia de Industria y Comercio, acorde con la Ley 1581 de 2012, el Decreto 1074 de 2015 y demás normas complementarias; (v) mis derechos y obligaciones, los puedo ejercer observando estrictamente la Política de Tratamiento de Información de ALPINA PRODUCTOS ALIMENTICIOS S.A. disponible en www.alpinago.com y (vi) el dato de contacto es: habeas.data@alpina.com. 
                            <p>En virtud de lo anterior, autorizo de manera previa, expresa, informada e inequívoca a ALPINA PRODUCTOS ALIMENTICIOS S.A. para que trate los datos que suministro en este documento para los fines señalados anteriormente. Adicionalmente, autorizo la transferencia o transmisión nacional e internacional de mis datos.</p>
                            <p>Declaro que los datos de terceros, los suministro tras haber obtenido previamente su autorización y en virtud de mi relación de parentesco con ellos.</p>
                </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<!--script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script-->
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/register_custom.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

<!--global js end-->


<script >
$(document).ready(function(){


     $('#password').on('focus', function() {
            $('#password').val('');
          });


        $('#password_confirm').on('focus', function() {
            $('#password_confirm').val('');
          });



        $("#state_id").select2();
        $("#city_id").select2();
        $("#id_type_doc").select2();
        $('#cod_alpinista').hide();

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