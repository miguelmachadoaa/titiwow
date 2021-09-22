@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carrito de Compras 
@parent
@stop


@section('meta_tags')
<meta property="og:title" content="Carrito de Compras | Alpina GO!">
<meta property="og:image" content="{{$configuracion->seo_image}}" />
<meta property="og:url" content="{{$url}}" />
<meta property="og:description" content="Carrito de Compras">

@if(isset($url))
<link rel="canonical" href="{{$url}}" />
@endif
@endsection

{{-- page level styles --}}
@section('header_styles')

 <link rel="canonical" href="{{secure_url('order/detail')}}" />


<!-- modal css -->

    <link href="{{ secure_asset('assets/css/pages/advmodals.css') }}" rel="stylesheet"/>

     <!--<link href="{{ secure_asset('assets/vendors/modal/css/component.css') }}" rel="stylesheet"/>-->

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>


    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <style type="text/css">
        
        .border{
            border:1px solid rgba(0,0,0,0.1);
            height: 4em;
        }

        .forma{
            margin-bottom: 1em;
    line-height: 4em;
        }

        .pointer{
            cursor: pointer;
        }

        .dl-horizontal dd {
            margin-left: 110px;
        }

        .dl-horizontal dt {
            float: left;
            width: 100px;
            overflow: hidden;
            clear: left;
            text-align: right;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        button.mercadopago-button {
                background: transparent;
                width: 100%;
                height: 100%;
                font-size: 14px;
                margin: 0px;
                padding: 0px;
                font-family: 'PlutoBold';
        }



        div.overlay {
    display:        table;
    position:        fixed;
    top:            0;
    left:            0;
    width:            100%;
    height:            100%;
    z-index: 1;
}
div.overlay > div {
    display:        table-cell;
    width:            100%;
    height:            100%;
    background:        #ccc;
    opacity:        1.5;
    text-align:        center;
    vertical-align:    middle;
}

.panel-title a {
    color: #3DC639;
}
.modal-title{
    color: #ffffff !important;
}

.help-block {
    display: block;
    margin-top: 5px;
    margin-bottom: 10px;
    color: #e80f0f;
}



    </style>

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

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="{{secure_url('productos')}}">Checkout</a>
                </li>
            </ol>
           
        </div>
    </div>
@stop

{{-- Page content --}}
@section('content')

<div style="display:none;" class="overlay"><div>Procesando...</div></div>


<div class="container contain_body container_cart_detail ">

   
<div data-cart="{{json_encode($cart)}}"></div>


    <div class="col-sm-6">
        
        @if(count($cart))
          
         <br>


         @if (session('aviso'))
            <div class="alert alert-danger">
                {{ session('aviso') }}
            </div>
        @endif

        <div class="res_direccion"></div>

        @include('frontend.includes.detallesventa')

    </div> <!-- end Row --><!-- col-sm-8 -->

    <div class="col-sm-6">

        @include('frontend.includes.direcciones')

        @include('frontend.includes.formasenvio')

        @include('frontend.includes.formaspago')

    </div>

    <br> 

</div> <!-- Container  -->

@else

<h2><span class="label label-primary">Tu Carrito de Compras está Vacio</span></h2>

@endif

<hr>

<p style="text-align: center;">

    <a class="btn btn-danger" href="{{secure_url('/cart/vaciar')}}">Cancelar <i class="fa fa-times" aria-hidden="true"></i></a>

</p>

<!--{!! Form::close() !!}-->

</div>
                    <!-- Step #2 -->
  



<!-- Modal Direccion -->
<div class="modal fade" id="modalCreditCard" role="dialog" aria-labelledby="modalLabeldanger">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalLabeldanger">Pago con Tarjeta De Credito o  Debito</h4>
            </div>
            <div class="modal-body" style="    padding: 2em;">

                <form id="form-checkout" class="form-horizontal">

                    <h3>Ingrese los siguientes datos para procesar su compra.</h3>

                    <div class="row">

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Numero de Tarjeta</label>

                            <div class="col-sm-8">

                            <input class="form-control" type="text" name="cardNumber" id="form-checkout__cardNumber" placeholder="Número de Tarjeta" />

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Tipo de Tarjeta</label>

                            <div class="col-sm-8">

                            <select class="form-control" name="issuer" id="form-checkout__issuer"></select>

                            </div>

                            </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Fache Vencimiento / CVV</label>

                            <div class="col-sm-3">

                            <input class="form-control" type="text" name="cardExpirationMonth" id="form-checkout__cardExpirationMonth"  placeholder="MM"/>    

                            </div>

                            <div class="col-sm-3">

                                <input class="form-control" type="text" name="cardExpirationYear" id="form-checkout__cardExpirationYear" placeholder="AA" />  

                            </div>


                            <div class="col-sm-2">

                            <input class="form-control" type="text" name="securityCode" id="form-checkout__securityCode" placeholder="CVV" />

                            </div>



                        </div>


                       

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Nombre del Titular</label>

                            <div class="col-sm-8">

                            <input class="form-control" type="text" name="cardholderName" id="form-checkout__cardholderName" placeholder="Nombre del Titular"/>

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Email del Titular</label>

                            <div class="col-sm-8">

                            <input class="form-control" type="email" name="cardholderEmail" id="form-checkout__cardholderEmail" placeholder="Email del Titular"/>

                            </div>

                        </div>



                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Tipo de Documento</label>

                            <div class="col-sm-8">

                            <select class="form-control" name="identificationType" id="form-checkout__identificationType" placeholder="Tipo de Documento"></select>

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Numero de Documento</label>

                            <div class="col-sm-8">

                            <input class="form-control" type="text" name="identificationNumber" id="form-checkout__identificationNumber" placeholder="Número de Documento"/>

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Cuotas</label>

                            <div class="col-sm-8">

                            <select class="form-control" name="installments" id="form-checkout__installments"></select>

                            </div>

                        </div>


                        <div class="form-group clearfix">

                            <button class="btn btn-primary" style=" text-align: right; " type="submit" id="form-checkout__submit">Pagar</button>

                        </div>




                      
                        <progress value="0" class="progress-bar">Cargando...</progress>
                    </form>


                    </div>

                </form>

                    <div class="row resPse" ></div>
                
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn  btn-primary sendPse" >Continuar</button>
            </div>
        </div>
    </div>
</div>









<!-- Modal Direccion -->
 <div class="modal fade" id="modalPse" role="dialog" aria-labelledby="modalLabeldanger">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalLabeldanger">Pago con PSE</h4>
            </div>
            <div class="modal-body" style="    padding: 2em;">

                <form method="POST" action="{{secure_url('cart/storedir')}}" id="addPseForm" name="addPseForm" class="form-horizontal">

                    <h3>Ingrese los siguientes datos para procesar su compra.</h3>

                    <div class="row">

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Nombre</label>

                            <div class="col-sm-8">

                                <input style="margin: 4px 0;" id="nombre_pse " name="nombre_pse " type="text" placeholder="Nombre Cliente" class="form-control">

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Tipo de Documento</label>

                            <div class="col-sm-8">

                                 <select class="form-control required" title="Selecciona Tipo de Documento..." name="id_type_doc_pse" id="id_type_doc_pse">

                                    <option value="">Selecciona el Tipo de Documento *</option>
                                    @foreach($tdocumento as $tdocument)
                                        <option value="{{ $tdocument->abrev_tipo_documento }}">{{ $tdocument->nombre_tipo_documento }} - {{ $tdocument->abrev_tipo_documento }}</option>

                                    @endforeach

                                 </select>

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Documento </label>

                            <div class="col-sm-8">

                                <input id="doc_cliente_pse" name="doc_cliente_pse" type="text"
                                               placeholder="Número de Documento" class="form-control required"/>
                                       
                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Email</label>

                            <div class="col-sm-8">

                                <input style="margin: 4px 0;" id="email_pse" name="email_pse" type="text" placeholder="Email" class="form-control">

                            </div>

                        </div>

                        <div class="form-group clearfix">

                            <label class="col-md-3 control-label" for="nombre_producto">Entidad Financiera</label>

                            <div class="col-sm-8">

                                <select class="form-control required" title="Entidad Financiera." name="id_fi_pse" id="id_fi_pse">

                                 
                                   <option value="">Selecciona Entidad Financiera *</option>
                                   @if(isset($payment_methods['body'] ))
                                    
                                    @foreach($payment_methods['body'] as $pm)

                                        @if($pm['id']=='pse')

                                            @foreach($pm['financial_institutions'] as $fi)

                                                <option value="{{ $fi['id'] }}">{{ $fi['description'] }}</option>

                                            @endforeach

                                        @endif

                                    @endforeach

                                    @endif
                                </select>

                            </div>

                        </div>

                    </div>

                </form>

                    <div class="row resPse" ></div>
                
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn  btn-primary sendPse" >Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Direccion -->

<!-- Modal Direccion -->
 <div class="modal fade" id="modalCupones" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger" style="color: #fff !important;">Aplicar Cupón</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('cart/addcuponform')}}" id="addCuponForm" name="addCuponForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Cupón</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="codigo_cupon" name="codigo_cupon" type="text" placeholder="Codigo de Cupón" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row resCupon" ></div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendCupon" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="modalCancelado" role="dialog" aria-labelledby="modalLabeldanger" data-backdrop="static" data-keyboard="false">

            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header bg-primary">

                        <h4 class="modal-title" id="modalLabeldanger" style="color: #fff !important;">Orden Cancelada</h4>

                    </div>

                    <div class="modal-body">

                        

                      <h3>Esta orden ha sido cancelada, sera redirigido al carrito para procesarla nuevamente. </h3>



                    </div>

                    <div class="modal-footer">

                        

                        <button type="button" class="btn  btn-primary sendOrdenCancelada" >Aceptar</button>

                    </div>

                </div>

            </div>

        </div>



<div class="modal fade" id="modalAprobado" role="dialog" aria-labelledby="modalLabeldanger" data-backdrop="static" data-keyboard="false">

            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header bg-primary">

                        <h4 class="modal-title" id="modalLabeldanger" style="color: #fff !important;">Orden Recibida</h4>

                    </div>

                    <div class="modal-body">

                        

                      <h3>Esta orden ha sido recibida, sera redirigido al area de clientes para que consulte el detalle. </h3>



                    </div>

                    <div class="modal-footer">

                        

                        <button type="button" class="btn  btn-primary sendOrdenRecibida" >Aceptar</button>

                    </div>

                </div>

            </div>

        </div>


@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
       const mp = new MercadoPago('{{$almacen->public_key_mercadopago}}');
       // Add step #3

       // Step #3
        const cardForm = mp.cardForm({
        amount: "{{$total}}",
        autoMount: true,
        form: {
            id: "form-checkout",
            cardholderName: {
            id: "form-checkout__cardholderName",
            placeholder: "Titular de la tarjeta",
            },
            cardholderEmail: {
            id: "form-checkout__cardholderEmail",
            placeholder: "E-mail",
            },
            cardNumber: {
            id: "form-checkout__cardNumber",
            placeholder: "Número de la tarjeta",
            },
            cardExpirationMonth: {
            id: "form-checkout__cardExpirationMonth",
            placeholder: "Mes de vencimiento",
            },
            cardExpirationYear: {
            id: "form-checkout__cardExpirationYear",
            placeholder: "Año de vencimiento",
            },
            securityCode: {
            id: "form-checkout__securityCode",
            placeholder: "Código de seguridad",
            },
            installments: {
            id: "form-checkout__installments",
            placeholder: "Cuotas",
            },
            identificationType: {
            id: "form-checkout__identificationType",
            placeholder: "Tipo de documento",
            },
            identificationNumber: {
            id: "form-checkout__identificationNumber",
            placeholder: "Número de documento",
            },
            issuer: {
            id: "form-checkout__issuer",
            placeholder: "Banco emisor",
            },
        },
        callbacks: {
            onFormMounted: error => {
            if (error) return console.warn("Form Mounted handling error: ", error);
            console.log("Form mounted");
            },
            onSubmit: event => {
            event.preventDefault();

            const {
                paymentMethodId: payment_method_id,
                issuerId: issuer_id,
                cardholderEmail: email,
                amount,
                token,
                installments,
                identificationNumber,
                identificationType,
            } = cardForm.getCardFormData();

            fetch("/order/creditcard", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                token,
                issuer_id,
                payment_method_id,
                transaction_amount: Number(amount),
                installments: Number(installments),
                description: "Compra AlpinaGo",
                payer: {
                    email,
                    identification: {
                    type: identificationType,
                    number: identificationNumber,
                    },
                },
                }),
            });
            },
            onFetching: (resource) => {
            console.log("Fetching resource: ", resource);

            // Animate progress bar
            const progressBar = document.querySelector(".progress-bar");
            progressBar.removeAttribute("value");

            return () => {
                progressBar.setAttribute("value", "0");
            };
            },
        },
        });


   </script>

    
    <script>

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
        'event': 'checkout',
        'total': '{{ $total }}',
        'productos':{!!json_encode($dl_productos)!!}
        });
    


</script>


    <script>


        $(document).ready(function(){

            $("input[name=options]").click(function () {    

                rid=$(this).attr('id');


                if (rid=='pago_total') {

                    $('#bono_use').val($('#monto_total_compra_bono_icg').val());
                    $('.mensaje_abono_usar').html('Usar: <b>'+$('#monto_total_compra_bono_icg').val()+'</b>');
                    $('#bono_use').attr('type', 'hidden');
                  //  $('.btnbonoh').html('Procesar <i class="fa  fa-chevron-right"></i>');
                    $('.contenidobono').removeClass('hidden');

                }else{

                    $('.mensaje_abono_usar').html('Usar: ');

                    $('#bono_use').val('0');
                    $('#bono_use').attr('type', 'text');
                  //  $('.btnbonoh').html('Aplicar <i class="fa  fa-chevron-right"></i>');
                    $('.contenidobono').removeClass('hidden');

                }

                //alert("La edad seleccionada es: " + rid);
            });


        });




        $('.addDescuentoIcg').on('click', function(){

            $(location).attr("href", '{{secure_url('cart/adddescuentoicg')}}');

        });

        $('.sendOrdenCancelada').on('click', function(){

             $('#modalCancelado').modal('show');

                 window.location.href = base+'/cart/show';


        });


        $('.sendOrdenRecibida').on('click', function(){

             $('#modalAprobada').modal('show');

                 window.location.href = base+'/clientes';

        });


         $('.showAddAddress').on('click', function(){

                if($('#addAddressForm').hasClass('open')){

                    $('#addAddressForm').removeClass('open');

                    $('#addAddressForm').fadeOut();

                }else{

                    $('#addAddressForm').addClass('open');

                    $('#addAddressForm').fadeIn();
                }

    

        });

         

        $(document).ready(function(){


        fe=$("#id_forma_envio").val();

        $('.dfe').fadeOut();

        $('#descripcion'+fe+'').fadeIn();








            $('button.mercadopago-button').hide();


             var aviso = localStorage.getItem("aviso");

             localStorage.setItem("aviso", '');

             if (aviso==undefined) {}else{

                if (aviso=='') {}else{

                    $('.resaviso').html(aviso);
                }

             }


        });



        /*funciones para cpones */

        $('body').on('click', '.cupones', function (){

            $('#modalCupones').modal('show');

       });

$('.delCupon').on('click', function(){

    id=$(this).data('id');

    _token=$('input[name="_token"]').val();

    var base = $('#base').val();

        $.ajax({
            type: "POST",

            data:{id, _token},

            url: base+"/cart/delcupon",
                
            complete: function(datos){     

                localStorage.setItem("aviso", datos.responseText);

                location.reload();
            
            }

        });

});


$('.delBono').on('click', function(){

    id=$(this).data('id');

    _token=$('input[name="_token"]').val();

    var base = $('#base').val();

        $.ajax({
            type: "POST",

            data:{id, _token},

            url: base+"/cart/delbono",
                
            complete: function(datos){     

                localStorage.setItem("aviso", datos.responseText);

                location.reload();
            
            }

        });

});




$('.delCuponIcg').on('click', function(){

    id=$(this).data('id');

    _token=$('input[name="_token"]').val();

    var base = $('#base').val();

        $.ajax({
            type: "POST",

            data:{id, _token},

            url: base+"/cart/delcuponicg",
                
            complete: function(datos){     

                localStorage.setItem("aviso", datos.responseText);

                location.reload();
            
            }

        });

});


var bancupon=true;


$('.sendCupon').click(function () {
    
    var $validator = $('#addCuponForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        if (bancupon) {

            bancupon=false;

            codigo_cupon=$("#codigo_cupon").val();

            var base = $('#base').val();

            $.ajax({
                type: "POST",
                data:{codigo_cupon},

                url: base+"/cart/addcupon",
                    
                complete: function(datos){     

                    bancupon=true;

                    //$(".container_cart_detail").html(datos.responseText);

                    localStorage.setItem("aviso", datos.responseText);

                    location.reload();

                    $('#modalCupones').modal('hide');

                   $("#nickname_address").val('');
                    $("#city_id").val('');
                    $("#calle_address").val('');
                    $("#calle2_address").val('');
                    $("#codigo_postal_address").val('');
                   $("#telefono_address").val('');
                    $("#notas").val('');
                
                }
            });

        }
        
        //document.getElementById("addDireccionForm").submit();
    }else{
        bancupon=true;
    }

});



   $(document).on('click', '.pse', function (){


        //llenamos los campos necesarios para procesar 

        id_direccion= $("#id_direccion").val(); 
        
        //id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 

        id_forma_envio=$("#id_forma_envio").val(); 
        
        id_forma_pago=$(this).data('id');


        $('#modalPse').modal('show');

});




  $('.sendPse').click(function () {

    $('.sendPse').attr('disable', true);

    var $validator = $('#addPseForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        nombre=$("#nombre_pse").val();

        id_type_doc=$("#id_type_doc_pse").val();

        doc_cliente=$("#doc_cliente_pse").val();

        email=$("#email_pse").val();

        id_fi=$("#id_fi_pse").val();

       // alert(nombre+' '+id_type_doc+' '+doc_cliente+' '+email+' '+id_fi);

        /*********************************************/ 

        id_direccion= $("#id_direccion").val(); 
            
       // id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 

        id_forma_envio=$("#id_forma_envio").val(); 

            
        id_forma_pago='2';

        var base = $('#base').val();

        var banpago = $('#banpago').val();
        
        

        if (banpago==0) {

            $('#banpago').val('1');

             $.ajax({
                type: "POST",

                data:{id_forma_envio, id_direccion, id_forma_pago},

                url: base+"/cart/verificarDireccion",
                    
                complete: function(datos){     

                    $('.sendPse').removeAttr('disable');

                    $('#banpago').val('0');

                   if(datos.responseText=='true'){


                        //$('#procesarForm').submit();

                        $.ajax({
                            type: "POST",
                            data:{nombre, id_type_doc, doc_cliente, email, id_fi},

                            url: base+"/order/getpse",
                                
                            complete: function(datos){  

                                

                               if(datos.responseText.substr(0, 5)=='https'){

                                //alert(datos.responseText);

                                  $(location).attr("href", datos.responseText);

                               }else{

                                $('.res_direccion').html('<div class="alert alert-danger" role="alert">Ha ocurrido un error, intente nuevamente.</div>');

                               }
                                                        
                            }

                        });

                    }else{

                        if (datos.responseText=='falseicg') {

                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                         $('#modalPse').modal('hidden');


                        }else if(datos.responseText=='falseCancelado'){

                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');


                             $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                         $('#modalPse').modal('hidden');

                        }else if(datos.responseText=='falseAprobado'){

                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue Aprobado por favor, dirijase al area de cliente..</div>');


                             $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                         $('#modalPse').modal('hidden');
                        }else{

                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                         $('#modalPse').modal('hidden');
                        }

                        

                    }
                
                }
            });


     }

        //document.getElementById("addDireccionForm").submit();
    }else{
        $('.sendPse').removeAttr('disable');
    }

});

        /*Funciones para cipones */

        $('body').on('click', '.procesar', function (){

            //$('.overlay').fadeIn();
            bono_use= $("#bono_use").val();

            id_direccion= $("#id_direccion").val(); 
            
            //id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 

            id_forma_envio=$("#id_forma_envio").val(); 

            id_forma_pago=$(this).data('id');

            if (id_forma_envio==undefined || id_direccion==undefined || id_forma_pago==undefined) {

                $('.res_direccion').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');

            }else{

                id_direccion= $("#id_direccion").val(); 
            
                id_forma_envio=$("#id_forma_envio").val(); 

                id_forma_pago=$(this).data('id');

                base=$('#base').val();
                

                if (id_forma_pago==2) {

                    type=$(this).data('type');
                    idpago=$(this).data('idpago');

                    if(type=="ticket"){

                        var banpago = $('#banpago').val();

                        if (banpago==0) {

                            $('#banpago').val('1');

                            $.ajax({
                                type: "POST",
                                data:{id_forma_envio, id_direccion, id_forma_pago},

                                url: base+"/cart/verificarDireccion",
                                    
                                complete: function(datos){     

                                    $('#banpago').val('0');

                                   if(datos.responseText=='true'){

                                        $('#procesarForm').submit();

                                        $.ajax({
                                            type: "POST",
                                            data:{id_direccion, id_forma_envio, id_forma_pago, type, idpago},

                                            url: base+"/order/procesarticket",
                                                
                                            complete: function(datos){     

                                                  

                                                $(location).attr("href", datos.responseText);


                                                //$('.contain_body').html(datos.responseText);

                                                //$('.overlay').fadeOut();
                                            
                                            }

                                        });

                                    }else{

                                       // $('.overlay').hidden();

                                         if (datos.responseText=='falseicg') {

                                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                                         $('#modalPse').modal('hidden');


                                        }else if(datos.responseText=='falseCancelado'){

                                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');
                                             $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                                         $('#modalPse').modal('hidden');
                                        }else{

                                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                                         $('#modalPse').modal('hidden');
                                        }

                                    }
                                
                                }
                            });

                        }

                    }else{


                    }

                }else if (id_forma_pago==4) {

                    type=$(this).data('type');

                    idpago=$(this).data('idpago');

                    bono_use=$('#bono_use').val();

                    var banpago = $('#banpago').val();

                    

                    

                        if (banpago==0) {

                            

                            $('#banpago').val('1');

                            $.ajax({
                                type: "POST",
                                data:{id_forma_envio, id_direccion, id_forma_pago},

                                url: base+"/cart/verificarDireccion",
                                    
                                complete: function(datos){     

                                    $('#banpago').val('0');

                                   if(datos.responseText=='true'){

                                        $('#procesarForm').submit();

                                        $.ajax({
                                            type: "POST",
                                            data:{id_direccion, id_forma_envio, id_forma_pago, type, idpago, bono_use},

                                            url: base+"/order/procesarbono",
                                                
                                            complete: function(datos){     

                                               $(location).attr("href", datos.responseText);
                                            
                                            }

                                        });

                                    }else{

                                       // $('.overlay').hidden();

                                         if (datos.responseText=='falseicg') {

                                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                                         $('#modalPse').modal('hidden');


                                        }else if(datos.responseText=='falseCancelado'){

                                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');
                                             $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                                         $('#modalPse').modal('hidden');
                                        }else{

                                            $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                                         $('#modalPse').modal('hidden');
                                        }

                                    }
                                
                                }
                            });

                        }

                    

                }else{

                    var banpago = $('#banpago').val();

                    

                        if (banpago==0) {

                        $('#banpago').val('1');

                        $.ajax({
                            type: "POST",
                            data:{id_forma_envio, id_direccion, id_forma_pago},
                            url: base+"/cart/verificarDireccion",
                            
                            complete: function(datos){     

                                $('#banpago').val('0');

                                if(datos.responseText=='true'){

                                //$('#procesarForm').submit();

                                    $.ajax({
                                        type: "POST",
                                        data:{id_direccion, id_forma_envio, id_forma_pago},
                                        url: base+"/order/procesar",
                                        
                                        complete: function(datos){     

                                             

                                             $(location).attr("href", datos.responseText);

                                            //$('.contain_body').html(datos.responseText);

                                            $('.overlay').fadeOut();
                                    
                                        }

                                    });

                                }else{

                                     if (datos.responseText=='falseicg') {

                                        $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                                     $('#modalPse').modal('hidden');


                                    }else if(datos.responseText=='falseCancelado'){

                                        $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');
                                         $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                                     $('#modalPse').modal('hidden');
                                    }else{

                                        $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                                     $('#modalPse').modal('hidden');
                                    }

                                }
                        
                            }
                        
                        });

                    }

                }

            }

        });


        $('body').on('click', '.mercadopago', function (){

            id_direccion= $("#id_direccion").val(); 
            
           // id_forma_envio=$("input[name='id_forma_envio']:checked").val();

            id_forma_envio=$("#id_forma_envio").val(); 

            
            id_forma_pago=$(this).data('id');

            url=$(this).data('href');


            if (id_forma_envio==undefined || id_direccion==undefined || id_forma_pago==undefined) {

               // alert('Todos los capos son obligatorios');

                $('.res_direccion').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');

            }else{

                $('#id_forma_pago').val(id_forma_pago);

                base=$('#base').val();

                 var banpago = $('#banpago').val();

                if (banpago==0) {

                $('#banpago').val('1');

                    $.ajax({
                        type: "POST",
                        data:{id_forma_envio, id_direccion, id_forma_pago},

                        url: base+"/cart/verificarDireccion",
                            
                        complete: function(datos){     

                            $('#banpago').val('0');

                           if(datos.responseText=='true'){

                                window.location.href = url;

                           }else{

                                 if (datos.responseText=='falseicg') {

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                                 $('#modalPse').modal('hidden');


                                }else if(datos.responseText=='falseCancelado'){

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');
                                     $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                                 $('#modalPse').modal('hidden');
                                }else{

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                                 $('#modalPse').modal('hidden');
                                }

                           }
                        
                        }
                    });

                }

            }

        });



          $('body').on('click', '#epayco', function (e){

           // e.preventDefault();

          
            id_direccion= $("#id_direccion").val(); 

            id_forma_envio=$("#id_forma_envio").val(); 
            

            
            id_forma_pago=$(this).data('id');

            url=$(this).data('href');


            if (id_forma_envio==undefined || id_direccion==undefined ||  id_forma_pago==undefined) {

               // alert('Todos los capos son obligatorios');

                $('.res_direccion').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');


            }else{

                $('#id_forma_pago').val(id_forma_pago);

                base=$('#base').val();

                var banpago = $('#banpago').val();
        
                console.log($('#banpago').val());

                if (banpago==0) {

                    $('#banpago').val('1');

                $.ajax({
                    type: "POST",
                    data:{id_forma_envio, id_direccion, id_forma_pago},

                    url: base+"/cart/verificarDireccion",
                        
                    complete: function(datos){     

                        $('#banpago').val('0');


                       if(datos.responseText=='true'){


                            $.ajax({
                                type: "GET",
                                url: base+"/cart/getOrden",

                                complete: function(datos){  

                                    $('.epayco-button').data('epayco-name', 'Compra XP'+datos.responseText);
                                    $('.epayco-button').data('epayco-descriptionx', 'Compra XP'+datos.responseText);

                                 $('.epayco-button-render').trigger('click'); 

                                }

                            });



                       }else{

                            if (datos.responseText=='falseicg') {

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                                 $('#modalPse').modal('hidden');


                                }else if(datos.responseText=='falseCancelado'){

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');
                                     $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                                 $('#modalPse').modal('hidden');
                                }else{

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                                 $('#modalPse').modal('hidden');
                                }

                       }
                    
                    }

                });

                }
            }

            

        });











        $('body').on('click', '#creditcard', function (e){

           // e.preventDefault();

            id_direccion= $("#id_direccion").val(); 
            
            //id_forma_envio=$("input[name='id_forma_envio']:checked").val(); 

            id_forma_envio=$("#id_forma_envio").val(); 

            
            id_forma_pago='2';

            url=$(this).data('href');

            if (id_forma_envio==undefined || id_direccion==undefined || id_forma_pago==undefined) {

               // alert('Todos los capos son obligatorios');

                $('.res_direccion').html('<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>');

            }else{

                $('#id_forma_pago').val(id_forma_pago);

                base=$('#base').val();

                var banpago = $('#banpago').val();

                

                if (banpago==0) {

                $('#banpago').val('1');

                    $.ajax({
                        type: "POST",
                        
                        data:{id_forma_envio, id_direccion, id_forma_pago},

                        url: base+"/cart/verificarDireccion",
                            
                        complete: function(datos){    

                        $('#banpago').val('0');

                           if(datos.responseText=='true'){

                               // $('button.mercadopago-button').trigger('click');    //modalcredito

                                $('#modalCreditCard').modal('show');

                           }else{

                                 if (datos.responseText=='falseicg') {

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Ocurrio un error al registrar descuento de ICG por favor intente nuevamente </div>');

                                 $('#modalPse').modal('hidden');


                                }else if(datos.responseText=='falseCancelado'){

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Su pedido fue cancelado por favor, realice la compra nuevamente.</div>');
                                     $('#modalCancelado').modal('show');

                           // window.location.href = base+'/cart/show';

                                 $('#modalPse').modal('hidden');
                                }else{

                                    $('.res_direccion').html('<div hidden class="alert alert-danger" role="alert">Esta ciudad no esta Disponible para envios.</div>');

                                 $('#modalPse').modal('hidden');
                                }

                           }
                        
                        }
                    });

                }

            }

        });


        jQuery(document).ready(function () {
            new WOW().init();
        });

        $('.addDireccionModal').on('click', function(){
            $("#addDireccionModal").modal('show');
        });



    </script>

    <!-- modal js -->

    <script type="text/javascript" src="{{ secure_asset('assets/vendors/modal/js/classie.js')}}"></script>
    <script>
        $("#stack2,#stack3").on('hidden.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });
    </script>

     <script type="text/javascript">

$("#addCuponForm").bootstrapValidator({
    fields: {
        codigo_cupon: {
            validators: {
                notEmpty: {
                    message: 'El Código es requerido '
                }
            },
            required: true,
            minlength: 3
        }
    }
});


$("#addPseForm").bootstrapValidator({
    fields: {
        nombre_pse: {
            validators: {
                notEmpty: {
                    message: 'Nombre es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        id_type_doc_pse: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar un tipo de documento'
                }
            }
        },
        doc_cliente_pse: {
            validators: {
                notEmpty: {
                    message: 'Numero de Documento  es requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
        
        email_pse: {
            validators: {
                notEmpty: {
                    message: 'Email no puede esta vacio'
                },
                emailAddress: {
                        message: 'No es un email valido'
                }
            },
        },

        id_fi_pse: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una Institucion Financiera'
                }
            }
        }
    }
});


        

$("#addDireccionForm").bootstrapValidator({
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
        
        detalle_address: {
            validators: {
                notEmpty: {
                    message: 'El Detalle de la dirección no puede estar vacio'
                }
            }
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



$('.sendDireccion').click(function () {
    
    var $validator = $('#addDireccionForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        titulo=$("#titulo").val();
        city_id=$("#city_id").val();
        calle_address=$("#calle_address").val();
        calle2_address=$("#calle2_address").val();
        codigo_postal_address=$("#codigo_postal_address").val();
        telefono_address=$("#telefono_address").val();
        notas=$("#notas").val();
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{nickname_address, city_id, calle_address, calle2_address, codigo_postal_address, telefono_address, notas},
            url: base+"/cart/storedir",
                
            complete: function(datos){     

                $(".direcciones").html(datos.responseText);

                $('#addDireccionModal').modal('hide');

               $("#nickname_address").val('');
                $("#city_id").val('');
                $("#calle_address").val('');
                $("#calle2_address").val('');
                $("#codigo_postal_address").val('');
               $("#telefono_address").val('');
                $("#notas").val('');
        
            
            }
        });


    }

});

$('#addDireccionForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });

    $(document).ready(function(){
        $('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })

    </script>


<script type="text/javascript">
        
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


    
    //fin select ciudad
});

    $('#id_forma_envio').change(function () {
    
                base=$('#base').val();

                _token=$('input[name="_token"]').val();

                id_forma_envio=$('#id_forma_envio').val();

                $.ajax({
                    type: "POST",
                    data:{  id_forma_envio, _token},
                    url: base+"/cart/setformaenvio",
                        
                    complete: function(datos){     

                      location.reload()    

                    }

                });

        });


    $('#id_direccion').change(function () {
    
                base=$('#base').val();

                _token=$('input[name="_token"]').val();

                id_direccion=$('#id_direccion').val();

                $.ajax({
                    type: "GET",
                    data:{  id_direccion, _token},
                    url: base+"/cart/setdir/"+id_direccion,
                        
                    complete: function(datos){     

                      location.reload();   

                     //alert(datos);

                    }

                });

        });


    $('input[type=radio][name=formaenvio]').change(function() {
       // alert($(this).val());

        base=$('#base').val();

        _token=$('input[name="_token"]').val();

        id_forma_envio=$(this).val();

        $.ajax({
            type: "POST",
            data:{  id_forma_envio, _token},
            url: base+"/cart/setformaenvio",
                
            complete: function(datos){     

              location.reload()    

            }

        });



    });

    </script>

@stop


@section('footer_scripts')



<!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T5HPJTZ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

   
@stop