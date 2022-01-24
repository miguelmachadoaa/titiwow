<!DOCTYPE html> 
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="canonical" href="{{secure_url('registro')}}" />


    <title>Registro | Alpina Go! </title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ secure_asset('assets/img/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{ secure_asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <style>
        
        .select2-container {
    width: 100% !important;
}


    </style>
    <!--end of global css-->
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
            <a href="{{ secure_url('/') }}"><img src="{{ secure_asset('assets/images/logo_go.png') }}" alt="Alpina GO!"></a>
            <div class="clearfix"></div>
            <h3 class="text-primary">Registro Alpina Go!</h3>
            <!-- Notifications -->
            <div id="notific">
            @include('notifications')
            </div>
            <form action="{{ secure_url('registro') }}" method="POST" id="reg_form" name="reg_form" autocomplete="off">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nombre"
                           value="{!! old('first_name') !!}" >
                    {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Apellido"
                           value="{!! old('last_name') !!}" >
                    {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('id_type_doc', 'has-error') }}">
                    <div class="" >
                        <select id="id_type_doc" name="id_type_doc" class="form-control {{ $errors->first('id_type_doc', 'has-error') }}">
                            <option value="">Seleccione Tipo de Documento</option>     
                            @foreach($t_documento as $tdoc)
                                <option value="{{ $tdoc->id }}" {{ (old("id_type_doc") == $tdoc->id ? "selected":"") }}>{{ $tdoc->abrev_tipo_documento}} - {{ $tdoc->nombre_tipo_documento}}</option>
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
                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <input type="email" class="form-control" id="Email" name="Email" placeholder="Email"
                           value="{!! old('email') !!}" >
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                    <div class="errorEmail"></div>
                </div>
                <div class="form-group {{ $errors->first('telefono_cliente', 'has-error') }}">
                    <input type="number" class="form-control" id="telefono_cliente" name="telefono_cliente" placeholder="Teléfono"
                           value="{!! old('telefono_cliente') !!}" >
                    {!! $errors->first('telefono_cliente', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <input type="password" class="form-control" id="Password1" name="password" placeholder="Contraseña">
                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                    <input type="password" class="form-control" id="Password2" name="password_confirm"
                           placeholder="Confirmar Contraseña">
                    {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                </div>
               

                <!--div class="form-group {{ $errors->first('convenio', 'has-error') }}">
                    <label for="">¿Tienes un número de convenio?  <small>(Opcional)</small></label>
                    <input type="text" class="form-control" value="{!! old('convenio') !!}" id="convenio" name="convenio" placeholder="Código de Convenio"
                           value="{!! old('convenio') !!}" >
                    <div class="res_convenio"></div>
                    {!! $errors->first('convenio', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div-->
                <hr />
                <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="chkalpinista" id="chkalpinista" value="1"> ¡Soy Alpinista! <small>(Opcional)</small> </a>
                    </label>
                </div>
                <div class="form-group {{ $errors->first('cod_alpinista', 'has-error') }}">
                    <input type="text" class="form-control" value="{!! old('cod_alpinista') !!}" id="cod_alpinista" name="cod_alpinista" placeholder="Código de Alpinista" value="{!! old('cod_alpinista') !!}" >
                    <div class="res_cod_alpinista"></div>

                    {!! $errors->first('cod_alpinista', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
               
                <div class="form-group {{ $errors->first('habeas_cliente', 'has-error') }} checkbox">
                    <label style="font-size:12px;">
                        <input type="checkbox" name="habeas_cliente" id="habeas_cliente" value="1" require> Autorizo y declaro que soy mayor de edad, que he leído y acepto el tratamiento de mis datos personales conforme al formato de autorización disponible <a href="{{ secure_asset('uploads/files/Formato_Autorizacion_Tratamientos_de_Datos_en_Medios_Digitales.pdf') }}" class="menu-item" target="_blank" title="Formato Autorización Tratamientos de Datos en Medios Digitales" alt="Formato Autorización Tratamientos de Datos en Medios Digitales">acá.</a> y Acepto los <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go
                    </label>
                    {!! $errors->first('habeas_cliente', '<span class="help-block">:message</span>') !!}
                </div>
                <!--div class="form-group {{ $errors->first('terminos_cliente', 'has-error') }} checkbox">
                    <label style="font-size:12px;">
                        <input type="checkbox" name="terminos_cliente" id="terminos_cliente" value="1" require> Acepto los <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go</a>
                    </label>
                    {!! $errors->first('terminos_cliente', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div-->
                <!--div class="form-group">
                    <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_KEY')}}" style="padding:10px 30px !important"></div>
                    {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
                </div-->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <button id="btnsubmit" name="btnsubmit" type="button" class="btn btn-block btn-primary">Registrarse</button>
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
        <!--div>
            <div class="form-group">
                <div class="row text-center" style="background:rgba(0,0,0,0.4);color:#ffffff;padding:20px;margin-top:20px;">
                    <div class="col-md-12">
                        <div style="font-size:12px;padding:0px 20px;">
                            <p>He sido informado (a) por ALPINA PRODUCTOS ALIMENTICIOS S.A. (Responsable del tratamiento) de lo siguiente: (i) los datos suministrados en este documento serán tratados para los siguientes propósitos: enviar o utilizar la información para fines contractuales, de atención al cliente, de marketing (tales como análisis de consumos, trazabilidad de marca entre otros), comerciales, actualizar datos y brindar información relevante; (ii) es facultativo responder preguntas sobre datos sensibles o de menores de edad; (iii) como titular de los datos y/o representante del menor, tengo los derechos de conocer, actualizar, rectificar o suprimir mi información o revocar esta autorización; (iv) en caso de no ser resuelta mi solicitud directamente, y de manera subsidiaria, tengo derecho a presentar quejas ante la Superintendencia de Industria y Comercio, acorde con la Ley 1581 de 2012, el Decreto 1074 de 2015 y demás normas complementarias; (v) mis derechos y obligaciones, los puedo ejercer observando estrictamente la Política de Tratamiento de Información de ALPINA PRODUCTOS ALIMENTICIOS S.A. disponible en www.alpinago.com y (vi) el dato de contacto es: habeas.data@alpina.com. 
                            <p>En virtud de lo anterior, autorizo de manera previa, expresa, informada e inequívoca a ALPINA PRODUCTOS ALIMENTICIOS S.A. para que trate los datos que suministro en este documento para los fines señalados anteriormente. Adicionalmente, autorizo la transferencia o transmisión nacional e internacional de mis datos.</p>
                            <p>Declaro que los datos de terceros, los suministro tras haber obtenido previamente su autorización y en virtud de mi relación de parentesco con ellos.</p>
                </p>
                            <p><a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" style="color:#ffffff !important;" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go</a> | <a href="{{ secure_asset('uploads/files/politica_de_tratamiento_de_la_informacion.pdf') }}" class="menu-item" style="color:#ffffff !important;" target="_blank" title="Políticas de Tratamiento de la Información" alt="Políticas de Tratamiento de la Información">Políticas de Tratamiento de la Información</a>
</p>
                        </div>
                    </div>
                </div>
            </div>
        </div-->
    </div>
    <!-- //Content Section End -->

<div class="modal fade" id="ModalConvenido" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Error</h4>
            </div>
            <div class="modal-body">
               <h3>El código que intento utilizar no existe, ¿Desea registrarse sin convenio?.</h3> 
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ok</span></button>
            </div>
        </div>
    </div>
</div>





</div>
<!--global js starts-->
<script src='https://www.google.com/recaptcha/api.js?render=explicit'></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<!--script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script-->
<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/register_custom.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js?render=6LflWnsaAAAAAERsguImH7gK43wG2vehWYLSw63W"></script>

<script>
  grecaptcha.ready(function() {
  grecaptcha.execute('6LflWnsaAAAAAERsguImH7gK43wG2vehWYLSw63W', {action: 'contactForm'})
  .then(function(token) {

  var recaptchaResponse = document.getElementById('_recaptcha');
  recaptchaResponse.value = token;
  });});





</script>
<!--global js end-->
<script >
$(document).ready(function(){


    $("input#Password1").focus(function(){
        $(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#Password1").click(function(){
        $(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#Password1").keypress(function(){
        //$(this).val('');
        $(this).get(0).type = 'password';
    });


    $("input#Password2").focus(function(){
        $(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#Password2").click(function(){
        $(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#Password2").keypress(function(){
        //$(this).val('');
        $(this).get(0).type = 'password';
    });

    $("input#btnsubmit").keypress(function(){
        //$(this).val('');
        $("input#Password1").get(0).type = 'password';
        $("input#Password2").get(0).type = 'password';

    });


    

    $("input").keypress(function(e) {
       if(e.which == 13) {

           e.preventDefault();

          $( "#btnsubmit" ).trigger( "click" );
       }
    });



        $('#Password1').on('focus', function() {
            $('#Password1').val('');
          });


        $('#Password2').on('focus', function() {
            $('#Password2').val('');
          });


        $('#cod_alpinista').hide();
        $('#chkalpinista' ).on( 'click', function() {
            if( $(this).is(':checked') ){
                $('#cod_alpinista').show();
            } else {
                $('#cod_alpinista').hide();
                $('#cod_alpinista').val("");
            }
        });

        $('#cod_alpinista').change(function(){
                $('#btnsubmit').removeAttr('disabled');             
        })

        $(document).on('click','#btnsubmit', function(e){

            codigo=0;

            e.preventDefault();

            first_name=$('#first_name').val();
            last_name=$('#last_name').val();
            id_type_doc=$('#id_type_doc').val();
            doc_cliente=btoa($('#doc_cliente').val());
            email=$('#Email').val();
            telefono_cliente=$('#telefono_cliente').val();
            password=btoa($('#Password1').val());
            password_confirm=btoa($('#Password2').val());
        //    state_id=$('#state_id').val();
        //    city_id=$('#city_id').val();
        //    id_estructura_address=$('#id_estructura_address').val();
        //    principal_address=$('#principal_address').val();
        //    secundaria_address=$('#secundaria_address').val();
        //    detalle_address=$('#detalle_address').val();
        //    barrio_address=$('#barrio_address').val();
        //    id_barrio=$('#id_barrio').val();
         //   edificio_address=$('#edificio_address').val();
            habeas_cliente=$('#habeas_cliente').val();
            
            ban_enviar=0;

            

            base=$('#base').val();

            _token=$('input[name="_token"]').val();

            convenio=$('#convenio').val();

            if (convenio!='' && convenio!=undefined) {

                $.post('postconveniosregistro', { convenio, _token}, function(data) {

                    // alert(data+'d');

                    if (data==1) {

                        $('.res_cod_alpinista').html('');


                        var $validator = $('#reg_form').data('bootstrapValidator').validate();


                        if( $('#chkalpinista').is(':checked') ) {


                            if ($('#cod_alpinista').val()!='') {


                                if ($validator.isValid()) {


                                    //$("#reg_form")[0].submit();

                                    ban_enviar=1;

                                }

                            }else{


                                $('.res_cod_alpinista').html('<span class="help-block">Código de Alpinista es requerido</span>');

                                //$('#btnsubmit').attr('disabled', '1');
                            }

                        }else{


                            if ($validator.isValid()) {

                               //$("#reg_form")[0].submit();

                               ban_enviar=1;
                                   
                            }

                        }   

                    }else{

                        $('.res_convenio').html('<span class="help-block">El Código de convenio no existe</span>');

                        $('#convenio').val('');
                    }

                });

            }else{

                $('.res_cod_alpinista').html('');

                var $validator = $('#reg_form').data('bootstrapValidator').validate();

                if( $('#chkalpinista').is(':checked') ) {

                    if ($('#cod_alpinista').val()!='') {

                        if ($validator.isValid()) {

                             //$("#reg_form")[0].submit();

                             ban_enviar=1;

                        }

                    }else{

                        $('.res_cod_alpinista').html('<span class="help-block">Código de Alpinista es requerido</span>');

                        //$('#btnsubmit').attr('disabled', '1');

                    }

                }else{


                    if ($validator.isValid()) {

                       //$("#reg_form")[0].submit();

                       ban_enviar=1;
                           
                    }
                }

            }

            if(ban_enviar==1){

                $.ajax({
                    url: base+'/registro',
                    data:{
                        first_name:first_name,
                        last_name:last_name,
                        id_type_doc:id_type_doc,
                        doc_cliente:doc_cliente,
                        email:email,
                        telefono_cliente:telefono_cliente,
                        password:password,
                        password_confirm:password_confirm,
                        habeas_cliente,
                    //    state_id:state_id,
                    //    city_id:city_id,
                    //    id_estructura_address:id_estructura_address,
                    //    principal_address:principal_address,
                    //    secundaria_address:secundaria_address,
                    //    detalle_address:detalle_address,
                   //     barrio_address:barrio_address,
                    //    id_barrio:id_barrio,
                    //    habeas_cliente:habeas_cliente,
                    //    edificio_address:edificio_address,
                        _token:_token   
                    },  
                    type: "POST",
                    
                    success:function(data) {

                        if(data=='false'){

                            $('#notific').html('<div class="alert alert-danger">Error al crear el usuario intente nuevamente.</div>');
                            
                        }else if(data=='false1'){

                            $('#notific').html('<div class="alert alert-danger">Codigo de Registro no Existe.</div>');

                        }else if(data=='falseEmail'){

                            $('#notific').html('<div class="alert alert-danger">El Email ya fue usado.</div>');
                            $('.errorEmail').html('<div class="label label-danger">El Email ya fue usado.</div>');

                            

                        }else{

                         $(location).attr('href',data);

                        }
                            
                    }
                });

                }

            //alert(codigo);

        });


      //  $("#state_id").select2();
     //   $("#city_id").select2();
     //   $("#id_barrio").select2();
     //   $("#id_type_doc").select2();
     //   $("#id_estructura_address").select2();
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

                              //  console.log(JSON.stringify(data).length);

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


                                $("#id_barrio").select2({
                                    sortResults: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                                });



                            }
                        });
                    }else{

                        $('select[name="id_barrio"]').empty();

                    }
                });

            //fin select ciudad
        });

 </script>
</body>
</html>
