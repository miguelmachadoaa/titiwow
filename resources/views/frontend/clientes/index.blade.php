
@extends('layouts/pos')

{{-- Page title --}}
@section('title')
Punto de Venta
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

  <link rel="canonical" href="{{secure_url('clientes')}}" />

    <style type="text/css">

        .btn-medium-100 {
            min-height: 120px;
            line-height: 60px;
            min-width: 100% !important;
            margin-bottom: 1em;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.1);
            background: #fff !important;
            box-shadow: 2px 2px 2px #ddd;
            padding: 0em 1em;
        }


        
        .btn-medium {
            height: 120px;
            line-height: 120px;
            width: 120px;
            margin-bottom: 1em;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.1);
            background: #fff !important; 
            box-shadow: 2px 2px 2px #ddd;
        }


        .btn-medium {
            text-decoration: none;
            color: #000;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            transition: .2s ease-out;
            cursor: pointer;
        }

        .btn-medium i {
            font-size: 3.6rem;
        }

        h4 span {
    color: #007add;
}



    </style>
@stop


{{-- Page content --}}
@section('content')

<audio class="audio">
    <source src="{{url('assets/sounds/scanner-beep-checkout.mp3')}}" type="audio/mp3" style="display: none;">
</audio>

<div class="container-fluid contain_body">

    <div class="row">

        <div class=" col-sm-12">

            <div class="row barcode">

            @include('pos.barcode')
                
            </div>

            


        </div>

        <div class="col-sm-8 panelprincipal" style="height: 90vh; overflow: auto;">

            
            @include('pos.dashboard')

        </div> 


        <div class="col-sm-4 ordenactual" >

           @include('pos.ordenactual')

        </div> 

    </div>
    
</div>

<div class="modal pt" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transaccion Completada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bodypt">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary printpt" data-dismiss="modal">Imprimir</button>
        <button type="button" class="btn btn-danger closept" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal modalPesable" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ingrese el peso del producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bodypt">
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Agregar peso en gramos <small>1kilo = 1000grs</small></label>
            <input type="hidden" id="modalid" name="modalid" >
            <input type="number" step="1" min="0" value='0' class="form-control" name="peso" id="peso" aria-describedby="peso">
           
          </div>
         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary enviarPeso" >Aceptar</button>
        <button type="button" class="btn btn-danger close" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">


  
@endsection

<div class="row">
    <div class="col-sm-12 d-none" >
        {{json_encode($cart)}}
    </div>
</div>

<!-- Modal Direccion -->

{{-- page level scripts --}}
@section('footer_scripts')


<script>


    $(document).ready(function(){

    $(".audio")[0].play();

        $('#barcode').focus();

         $(document).on('keydown', 'body', function(event) {
            if(event.keyCode==182){ //F1
                event.preventDefault();

                $('#barcode').focus();
             }
         });


        $(document).on('keypress', '#cliente', function(e) {

        if(e.which == 13) {

            termino=$('#cliente').val();

            base=$('#base').val();

            $.ajax({
                    type: "POST",
                    data:{termino},
                    url: base+"/pos/buscarcliente",
                    dataType: 'JSON',
                        
                    complete: function(datos){     

                        $('#cliente').val('');

                            $('.panelprincipal').html((datos.responseText));

                    }

                });
          
            }
      });




      $(document).on('keypress', '#barcode', function(e) {

        if(e.which == 13) {

            termino=$('#barcode').val();

            base=$('#base').val();


            $.ajax({
                    type: "POST",
                    data:{termino},
                    url: base+"/pos/buscarproducto",
                    dataType: 'JSON',
                        
                    complete: function(datos){     

                        $('#barcode').val('');


                        console.log(datos);

                       // alert(datos.responseJSON.mensaje);
                       // 
                       if(datos.responseJSON.status=='productos'){

                            $('.panelprincipal').html((datos.responseJSON.data));
                            $('reserror').html('');

                       }else{

                            $('.ordenactual').html((datos.responseJSON.data));

                            termino=$('#barcode').val('');
                       }

                        
                    }

                });
          
        }
      });


      $(document).on('keypress', '#terminopedido', function(e) {
        
        if(e.which == 13) {

            termino=$(this).val();

            let expresion = new RegExp(`${termino}.*`, "i");

            $('.pedidos').each(function(index){

                    let ban=0;

                    console.log(index);

                    let data = $(this).data('json');

                    console.log(data);

                      // look for the entry with a matching `code` value
                      if (expresion.test(data.monto_total) ||  expresion.test(data.cliente.first_name) ||  expresion.test(data.cliente.last_name) ||  expresion.test(data.referencia) ||  expresion.test(data.id)){

                        $(this).fadeIn();

                      }else{

                        $(this).fadeOut();

                      }

            }) ;
          
        }


      });


       $(document).on('keypress', '#terminocliente', function(e) {
        
        if(e.which == 13) {

            termino=$(this).val();

            let expresion = new RegExp(`${termino}.*`, "i");

            $('.cliente').each(function(index){

                    let ban=0;

                    console.log(index);

                    let data = $(this).data('json');

                    console.log(data);

                      // look for the entry with a matching `code` value
                      if (expresion.test(data.first_name) ||  expresion.test(data.first_name) ||  expresion.test(data.telefono_cliente) ||  expresion.test(data.email) ||  expresion.test(data.id)){

                        $(this).fadeIn();

                      }else{

                        $(this).fadeOut();

                      }

            }) ;
          
        }


      });




        function getcarrito () {

            base=$('#base').val();

            $.ajax({
                    type: "POST",
                    data:{},
                    url: base+"/pos/getcarrito",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
                    }

                });
        }




        $(document).on('click', '.cajita', function(){

            vista=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "GET",
                    
                    url: base+"/pos/"+vista,

                    dataType: 'JSON',
                        
                    complete: function(datos){     

                        console.log(datos);
                        console.log(datos.responseJSON.status);

                        if(datos.responseJSON.status=='dashboard'){

                              $('.panelprincipal').html((datos.responseJSON.data));

                        }else if(datos.responseJSON.status=='login'){

                            location.reload();

                        }else{

                            $('.reserror').html((datos.responseJSON.mensaje));

                        }

                        getcarrito();
                    }

                });

        });


         $(document).on('click', '.asignacliente', function(){

            id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/asignacliente",
                        
                    complete: function(datos){     

                        $('.barcode').html((datos.responseText));
                    }

                });

        });


         $(document).on('click', '.removecliente', function(){

            id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/removecliente",
                        
                    complete: function(datos){     

                        $('.barcode').html((datos.responseText));
                    }

                });

        });



        $(document).on('click', '.saveproducto', function(){

            nombre_producto=$("#nombre_producto").val();
            precio=$("#precio").val();
            id_categoria=$("#id_categoria").val();
            id_impuesto=$("#id_impuesto").val();

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{nombre_producto, precio, id_categoria, id_impuesto},
                    url: base+"/pos/addproducto",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });



        $(document).on('click', '.savecaja', function(){

            baseinicial=$("#baseinicial").val();
            observacion=$("#observacion").val();

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{baseinicial, observacion},
                    url: base+"/pos/postcaja",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });


        $(document).on('click', '.updatecaja', function(){

            id=$("#idcaja").val();
            basefinal=$("#basefinal").val();
            observacion=$("#observacion").val();

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{basefinal, observacion,id},
                    url: base+"/pos/updatecaja",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });


        $(document).on('click', '.savecliente', function(){

            nombre_cliente=$("#nombre_cliente").val();
            telefono_cliente=$("#telefono_cliente").val();
            email_cliente=$("#email_cliente").val();
            cedula_cliente=$("#cedula_cliente").val();

            base=$('#base').val();


           // alert(nombre_cliente);

            $.ajax({
                type: "POST",
                data:{nombre_cliente, telefono_cliente, email_cliente, cedula_cliente},
                url: base+"/pos/addcliente",
                    
                complete: function(datos){     

                    $('.panelprincipal').html((datos.responseText));
                }

            });

        });


        $(document).on('click', '.producto', function(){

             id=$(this).data('id');
             cantidad=$('#cantidad_'+id+'').val();

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id, cantidad},
                    url: base+"/pos/addtocart",
                    dataType: 'JSON',
                        
                    complete: function(datos){

                        console.log(datos);
                        console.log(datos.responseJSON);

                        if(datos.responseJSON.status=='carrito'){

                            $('.ordenactual').html((datos.responseJSON.data));

                        }else if(datos.responseJSON.status=='error'){

                            $('.reserror').html('<div class="alert alert-danger">'+datos.responseJSON.mensaje+'</div>');

                            termino=$('#barcode').val('');
                        }     

                    }

                });

        });

        $(document).on('click', '.producto-pesable', function(){

            $('.modalPesable').modal('show');

             id=$(this).data('id');

             $("#modalid").val(id);

        });


         $(document).on('click', '.enviarPeso', function(){


            id=$('#modalid').val();

            cantidad=$('#peso').val();

            cantidad = cantidad / 1000;

            pesado=true;

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id, cantidad},
                    url: base+"/pos/addtocart",
                    dataType: 'JSON',
                        
                    complete: function(datos){

                            $('.modalPesable').modal('hide');

                            id=$('#modalid').val(0);

                            cantidad=$('#peso').val(0);


                        console.log(datos);

                        console.log(datos.responseJSON);

                        if(datos.responseJSON.status=='carrito'){

                            $('.ordenactual').html((datos.responseJSON.data));

                        }else if(datos.responseJSON.status=='error'){

                            $('.reserror').html('<div class="alert alert-danger">'+datos.responseJSON.mensaje+'</div>');

                            termino=$('#barcode').val('');
                        }     

                    }

                });

        });

        $(document).on('click', '.deltocart', function(){

             id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/deltocart",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
                    }

                });

        });

         $(document).on('click', '.vaciarcarrito', function(){

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{},
                    url: base+"/pos/vaciarcart",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
                    }

                });

        });


         $(document).on('click', '.guardarcarrito', function(){

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{},
                    url: base+"/pos/savecart",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
                    }

                });

        });


        $(document).on('click', '.delcarrito', function(){

             id=$(this).data('id');


            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/delcarrito",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });

         $(document).on('click', '.setcarrito', function(){

             id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/setcarrito",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
                    }

                });

        });


        $(document).on('click', '.pagar', function(){


            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{},
                    url: base+"/pos/pagar",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));

                        j.verificarPinPad();
                    }

                });

        });


        $(document).on('click', '.setpago', function(){

            name=$(this).data('name');

            id=$(this).data('id');

            $('.datapago').html('Forma de Pago '+name);

            $('#id_forma_pago').val(id);

            $('#nombre_forma_pago').val(name);

        });


        $(document).on('click', '.addpago', function(){

             id=$('#id_forma_pago').val();
             name=$('#nombre_forma_pago').val();
             monto=$('#monto_pago').val();
             referencia=$('#referencia').val();
             ticket=$('#ticket').val();

             if(id==0){

                $('#res').html('<div class="alert alert-danger ">Debe seleccionar una forma de pago </div>')
             }else{

                 base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id, name, monto, referencia, ticket},
                    url: base+"/pos/addpago",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });


             }


           

        });


        $(document).on('click', '.delpago', function(){

             id=$(this).data('id');


            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/delpago",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });


        $(document).on('click', '.detalleorden', function(){

            id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/detalleorden",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });


         $(document).on('click', '.detallecategoria', function(){

            id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/detallecategoria",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });

        $(document).on('click', '.detallecaja', function(){

            id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/detallecaja",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });


        $(document).on('click', '.cerrarcaja', function(){

            id=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/cerrarcaja",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });



        $(document).on('click', '.procesar', function(){

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{},
                    url: base+"/pos/procesar",
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));

                        getcarrito();
                    }

                });

        });


         $(document).on('click', '.getcarrito', function(){

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{},
                    url: base+"/pos/getcarrito",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
                    }

                });

        });


         $(document).on('click', '.btnImprimir', function(){

            base=$('#base').val();
            id=$(this).data('id');

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/imprimir",
                        
                    complete: function(datos){     

                       // alert(datos.responseText);

                       // $('.ordenactual').html((datos.responseText));
                    }

                });

        });

         //btnImprimirPunto
         //
         
         $(document).on('click', '.btnImprimirPunto', function(){

            base=$('#base').val();

            id=$(this).data('id');

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/imprimirpunto",
                        
                    complete: function(datos){     

                       // alert(datos.responseText);

                       // $('.ordenactual').html((datos.responseText));
                    }

                });

        });



         $(document).on('click', '.btnImprimirCaja', function(){

            base=$('#base').val();

            id=$(this).data('id');

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/imprimircierrecaja",
                        
                    complete: function(datos){     

                       // alert(datos.responseText);

                       // $('.ordenactual').html((datos.responseText));
                    }

                });

        });




    });
</script>







<script>

        function deshabilitar(){
            $('.pagarSitef').fadeOut();

            timeout = setTimeout(habilitar, 5000);
        }

        function habilitar(){
            $('.pagarSitef').fadeIn();
        }


            j = getSitef('http://localhost:5000',$);
            
            a = getSitef('http://localhost:5000', axios);

            j.on('MessageVerificar', (data) => {

                
                $('.resSitef').html('<div class="btn-medium-100">'+data.status+'</div>');


                console.info('datos MessageVerificar:',data.status)

            })

            j.on('statusMessage', (data) => {

                res='<br><button class="btn btn-primary m-1 finalizar" >Finalizar Transaccion</button>';

                if(data=='DESLICE / INSERTE TARJETA EN LECTORA'){

                     $('.resSitef').html('<div class="btn-medium-100">INSERTE TARJETA EN LA LECTORA'+res+'</div>');

                }else{

                     $('.resSitef').html('<div class="btn-medium-100">'+data+'</div>');
                }

                
                $('.resSitef').html('<div class="btn-medium-100">'+data+'</div>');


                console.info('datos statusMessage:',data);

            })

            j.on('transactionCompleted', (data) => {

                console.info('datos transactionCompleted:',data);

                console.log(data.responseValues[122]);

                $('.resSitef').html('<div class="btn-medium-100">'+data.responseValues[122]+'</div>');

                $('.bodypt').html('<div class="btn-medium-100">'+data.responseValues[122]+'</div>');

                $('.pt').modal('show');

                $('#referencia').val(data.responseValues[134]);

                $('#ticket').val(data.responseValues[122]);

                referencia=$('#referencia').val();
                monto_pago=$('#monto_pago').val();

                console.info('datos referencia:'+referencia);

                 j.finalizarTransaccion({saleInvoice: monto_pago, confirmationFlag:1});

                 j.finalizarTransaccion({confirmationFlag:1});

                 //$( ".addpago" ).trigger( "click" );

            })
            j.on('transactionError', (data) => {

                res='<br><button class="btn btn-primary m-1 finalizar" >Finalizar Transaccion</button>';
                $('.resSitef').html('<div class="btn-medium-100"> Ha ocurrido un Error en la Transaccion. Por Favor Intente Nuevamente. Si el problema persiste comuniquese con el administrador del Sistema.'+data+res+'</div>');  

               

                console.info('datos transactionError:', data);
            })
            j.on('transactionCanceled', (data) => {

                 $('.resSitef').html(data);

                console.info('datos transactionCanceled:', data)

            })
            j.on('confirm', (data,resolver) => {

                console.log('confirmar');

                console.log(data);

                $('.resSitef').html(data);

                /*if(data='DEBE COLECTAR MONTO DEL CAMBIO'){

                    resolver('1')

                }else if('INTRODUZCA EL CODIGO DE SUPERVISOR'){

                    resolver('1')
                
                }else{

                    $('.resSitef').html('<div class="btn-medium-100">'+data+'</div>');

                    const ret = window.confirm(data);

                    resolver(ret?'0':'1');

                }*/


                 const ret = window.confirm(data);

                    resolver(ret?'0':'1');

                
            })
            j.on('prompt', (data, resolver) => {

                console.log('prompt');

                console.log(data);

                if(data=='INTRODUZCA EL NUMERO DE LA CEDULA DE INDENTIDAD'){
                    data='INTRODUZCA EL NUMERO DE LA CEDULA DE IDENTIDAD';
                }

                if(data=='INTRODUZCA EL CODIGO DE SUPERVISOR'){

                    resolver('1');

                }else{



                    res='';

                    res=res+'<h3 class="mt-2">'+data+'</h3>  <input type="number" class="form-control mb-2" placeholder="'+data+'" name="promtdata" id="promtdata">  <button class="btn btn-primary promtbtn mb-2 mt-2" >Enviar</button>';
                    res=res+'<br><button class="btn btn-primary m-1 finalizar" >Finalizar Transaccion</button>';


                     $('.resSitef').html('<div class="btn-medium-100">'+res+'</div>');

                }


                 $(document).on('click', '.promtbtn', function(){

                    resolver(String($('#promtdata').val()));

                 })


               // const ret = window.prompt(data)

            })
            j.on('alert', (data, resolver) => {

                console.log('alert');

                console.log(data);

                 $('.resSitef').html(data);

                 if(data=='TRANS PENDIENTE'){
                    
                    res='<button class="btn btn-primary m-1 finalizar" >Finalizar Transaccion Pendiente</button>';

                     $('.resSitef').html(res);

                     referencia=$('#referencia').val();

                    j.finalizarTransaccion({saleInvoice: referencia, confirmationFlag:1});

                    j.finalizarTransaccion({confirmationFlag:1});

                 }

                window.alert(data);

                resolver(1)
            })
            j.on('select', (data, resolver) => {

                console.log('select');

                console.log(data);

                    res='';

                    res=res+'<h3>'+data.title+'</h3>';


                    for(let i=0; i< data.items.length; i++){

                        if(data.items[i].text=='CHEQUE'){

                        }else if(data.items[i].text=='CONFIRMACION DE PRE-AUTORIZACION'){
                        }else if(data.items[i].text=='PRE-AUTORIZACION'){
                        }else if(data.items[i].text=='REGISTRO DE PROPINA'){
                        }else if(data.items[i].text=='DEVOLUCION - PLATCO'){
                        }else if(data.items[i].text=='ESPECIAL'){
                        }else if(data.items[i].text=='LEALTAD'){
                        }else if(data.items[i].text=='OTRA CUENTA'){
                        }else if(data.items[i].text=='Carga forcada de tabelas no pinpad (Servidor)'){
                        }else if(data.items[i].text=='CONSULTA EXTRAFINANCIAMIENTO - CCC VENEZUELA'){
                        }else if(data.items[i].text=='VISTA'){

                            resolver('1');

                        }else{

                            if (data.items[i].text=='AHORRO') {

                                res=res+'<button class="btn btn-primary m-1 esperadata " data-id="'+data.items[i].value+'"> CUENTA AHORRO </button>';
                            //
                            }else if(data.items[i].text=='Fechamento de lote - Consorcio Venezuela'){
                                res=res+'<button class="btn btn-primary m-1 esperadata " data-id="'+data.items[i].value+'"> Cierre - Consorcio Venezuela </button>';
                            }else{

                                res=res+'<button class="btn btn-primary m-1 esperadata" data-id="'+data.items[i].value+'">'+data.items[i].text+'</button>';
                            }

                            

                        }
                        
                    }


                    //$('.resSitef').html(res);

                     $('.resSitef').html('<div class="btn-medium-100 p-2">'+res+'</div>');

                $(document).on('click', '.esperadata', function(){

                    if($(this).hasClass('btncuenta')){
                        $('#id_forma_pago').val($(this).data('id'));
                        $('#id_forma_pago').val($(this).text());
                    }

                    resolver(String($(this).data('id')));

                });


               // const ret = window.prompt(data.title+':'+JSON.stringify(data.items));
               // resolver(ret);
                
            })


            $(document).on('click','.pagarSitef',  function(){

                deshabilitar();

                monto=parseFloat($('#monto_pago').val());

                referencia=$('#referencia').val();

                console.log('referencia :'+referencia);

              //  j.finalizarTransaccion({saleInvoice: referencia, confirmationFlag:1})

                j.iniciarPago({saleInvoice:referencia,value:monto,operator:'test'});

            });


            $(document).on('click','.cancelarOrden',  function(){

                monto=parseFloat($('#monto_pago').val());

                referencia=$('#referencia').val();

                console.log('referencia :'+referencia);
                
                //j.anularTransaccion({saleInvoice:referencia, invoiceDate:'20220922',confirmationFlag:1});

                j.cancelarTransaccion();

            })


            $(document).on('click','.finalizar',  function(){

               // monto=parseFloat($('#monto_pago').val());
                
                //referencia=$('#referencia').val();

                //console.log('referencia :'+referencia);
                
                j.cancelarTransaccion();

                $('.resSitef').html('<div class="btn-medium-100 p-2">Transacción Finalizada</div>');

            })


            $(document).on('click','.mostrarMenu',  function(){

                j.menu({saleInvoice:'07',value:1.90,operator:'test'});

            })


            $(document).on('click','.verificarPinPad',  function(){

                j.verificarPinPad();

            });

            $(document).on('click','.printpt',  function(){

                $( ".addpago" ).trigger( "click" );

            })

            $(document).on('click','.closept',  function(){

                $( ".addpago" ).trigger( "click" );

            })


            


        </script>
  
@stop