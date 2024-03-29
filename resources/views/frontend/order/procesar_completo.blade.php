@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carro de Productos 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<!-- modal css -->

    <link href="{{ secure_asset('assets/css/pages/advmodals.css') }}" rel="stylesheet"/>

     <!--<link href="{{ secure_asset('assets/vendors/modal/css/component.css') }}" rel="stylesheet"/>-->

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <style>
        

        .texto_pago_yellow {
            color: #333 !important;
            margin-bottom: 15px !important;
            font-size: 22px !important;
        }


        .alert-yellow {
            color: #333;
            background-color: #ffff52!important;
            border-color: #ffff52!important;
        }


    </style>

@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">Carrito de Compra</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{secure_url('productos')}}">Resumen del Pedido</a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body text-center">
    <div class="row">

        @if($aviso_pago!='0')


            <div class="col-sm-12">
                <div class="alert alert-{{$aviso_pago['tipo']}}  alertita" >
                    
                   <span class="texto_pago_{{$aviso_pago['tipo']}}">{{ $aviso_pago['mensaje'] }}</span> 
                </div>
            </div>
        @endif

        <div class="col-sm-12">

            <h3>Gracias por su compra, recibirá un correo con el detalle de su pedido</h3>

            <h5>Su forma de Pago fue: <b>{{ $aviso_pago['medio'] }}</b> </h5>

            <h5>Ha seleccionado enviar el pedido con <b>{{ $compra->nombre_forma_envios }}</b>. Si quieres saber el estatus de tu pedido comunicate a la linea (+571)4238600 y al correo contaccenter@alpina.com</h5>



        <div class="row">
            
        <h1>Detalle de Su Pedido</h1>
        
        <br>
         <div class="col-md-10 col-md-offset-1 table-responsive">
         <table class="table  ">
                 <thead style="border-top: 1px solid rgba(0,0,0,0.1);">
                     <tr>
                         <th>Imagen</th>
                         <th>Producto</th>
                         <th>Precio</th>
                         <th>Cantidad</th>
                         <th>SubTotal</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($detalles as $row)
                        <tr>
                            <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" ><img height="60px" src="../uploads/productos/60/{{$row->imagen_producto}}"></a></td>
                            <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></td>
                            <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
                            <td> {{ $row->cantidad }} </td>
                            <td>{{ number_format($row->precio_total, 0,",",".") }}</td>
                        </tr>
                     @endforeach

                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Total: </b>
                         </td>
                         <td>
                             {{number_format($compra->monto_total, 0,",",".")}}
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Base Impuesto: </b>
                         </td>
                         <td>
                             {{number_format($compra->base_impuesto, 0,",",".")}}
                         </td>
                     </tr>

                     <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Iva:</b> {{ $compra->valor_impuesto*100 }}%: 
                         </td>
                         <td>
                             {{number_format($compra->monto_impuesto, 0,",",".")}}
                         </td>
                     </tr>

                       <tr>
                         <td colspan="4" style="text-align: right;">
                             <b>Monto Ahorrado: </b>
                         </td>
                         <td>
                             {{number_format($compra->monto_total_base-$compra->monto_total, 0,",",".")}}
                         </td>
                     </tr>

                     

                 </tbody>
             </table>

             <hr>

         </div>
     </div>


     <div class="row">
         <div class="col-md-10 col-md-offset-1 table-responsive" style="padding-bottom:20px;">
             
            <a class="label label-seguir"  href="{{ secure_url('/productos') }}">Seguir Comprando <i class="fa fa-plus" aria-hidden="true"></i></a>
         </div>
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





    <script>



        jQuery(document).ready(function () {
            new WOW().init();
        });

        $('.addDireccionModal').on('click', function(){
            $("#addDireccionModal").modal('show');
        })


    </script>

    <!-- modal js -->

    <script type="text/javascript" src="{{ secure_asset('assets/vendors/modal/js/classie.js')}}"></script>
    <script>
        $("#stack2,#stack3").on('hidden.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });
    </script>

     <script type="text/javascript">
        
        

$("#addDireccionForm").bootstrapValidator({
    fields: {
        nickname_address: {
            validators: {
                notEmpty: {
                    message: 'Nickname Direccion es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        calle_address: {
            validators: {
                notEmpty: {
                    message: 'Calle  es Requerido'
                    
                }
            },
            required: true,
            minlength: 3
        },
        codigo_postal_address: {
            validators: {
                notEmpty: {
                    message: 'Codigo Postal es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        telefono_address: {
            validators: {
                notEmpty: {
                    message: 'Telefono no puede esta vacion'
                }
            },
            minlength: 20
        },

       /* id_marca: {
            validators:{
                notEmpty:{
                    message: 'You must select a id_marca'
                }
            }
        }*/
    }
});



$('.sendDireccion').click(function () {
    
    var $validator = $('#addDireccionForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {



        document.getElementById("addDireccionForm").submit();
    }

});
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });
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
            //fin select ciudad
        });
    </script>



@stop


@section('footer_scripts')
    
@stop