
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

        
        <div class="col-sm-8 panelprincipal" style="height: 38em; overflow: auto;">
            
            @include('pos.dashboard')

        </div> 




        <div class="col-sm-4 ordenactual" >

           @include('pos.ordenactual')

        </div> 


    </div>
    
</div>


<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">

  
@endsection

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

                       }else{

                            $('.ordenactual').html((datos.responseJSON.data));
                            termino=$('#barcode').val('');
                       }

                        
                    }

                });



          
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
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));

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


        $(document).on('click', '.savecliente', function(){

            nombre_cliente=$("#nombre_cliente").val();
            telefono_cliente=$("#telefono_cliente").val();
            email_cliente=$("#email_cliente").val();
            cedula_cliente=$("#cedula_cliente").val();

            base=$('#base').val();


            alert(nombre_cliente);

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

            base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id},
                    url: base+"/pos/addtocart",
                        
                    complete: function(datos){     

                        $('.ordenactual').html((datos.responseText));
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

             if(id==0){

                $('#res').html('<div class="alert alert-danger ">Debe seleccionar una forma de pago </div>')
             }else{

                 base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{id, name, monto},
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




    });
</script>
  
@stop