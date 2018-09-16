@extends('layouts/default')

{{-- Page title --}}
@section('title')
Carro de Productos 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<!-- modal css -->

    <link href="{{ asset('assets/css/pages/advmodals.css') }}" rel="stylesheet"/>

     <!--<link href="{{ asset('assets/vendors/modal/css/component.css') }}" rel="stylesheet"/>-->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Dashboard
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="#">Carrito de Compra</a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{url('productos')}}">Productos</a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> Products
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container ">

    <div class="row">
        
        @if(count($cart))
          
         <br>
            <div class="row">   
                <div class="col-sm-10 col-sm-offset-1">  
                        <h3>    Direccion de Envio </h3>
                 </div>
            </div>

            <div class="row direcciones" style="text-align: left;">

                <div class="col-sm-12">

                @if(count($direcciones))
                
                    <div class="form-group col-sm-10 col-sm-offset-1">

                    @foreach($direcciones as $direccion)
                               
                        @if($direccion->default_address) 

                        <?php $c='checked'; ?>

                        @endif

                        <!-- Se construyen las opciones de envios -->

                        <div class="radio">

                            <label>

                                <input type="radio" name="id_direccion" class="custom-radio" id="id_direccion" value="{{ $direccion->id }}" {{ $c }}>

                                <p>

                                    <b>{{$direccion->nickname_address}}</b><br>

                                    Direccion: {{$direccion->calle_address.' '.$direccion->calle2_address}}<br>

                                    Codigo Postal: {{$direccion->codigo_postal_address}}<br>

                                    Telefono: {{$direccion->telefono_address}}<br>

                                    {{$direccion->country_name.', '.$direccion->state_name.', '.$direccion->city_name}}<br>

                                </p>

                            </label>

                        </div>
                   
                        <?php $c=''; ?>

                    @endforeach  

                    </div>

                </div>

                </div>

                <div class="row">

                <div class="col-sm-12" style="text-align: center;">

                    <button class="btn btn-raised btn-primary md-trigger addDireccionModal" data-toggle="modal" data-target="#modal-21">Agregar Nueva Direccion </button>

                </div>

                </div>


                <hr>


            <div class="row">

                    {!! Form::open(['url' => 'order/procesar', 'class' => 'form-horizontal', 'id' => 'procesarForm', 'name' => 'procesarForm', 'method'=>'POST']) !!}
                    @if(count($formasenvio))

                       

                        <div class="col-sm-10 col-sm-offset-1">

                             <div class="form-group col-sm-10 col-sm-offset-1">

                                <h3>    Formas de Envios</h3>

                                 <?php $c="checked"; ?>     

                                 <!-- Se construyen las opciones de envios -->                   

                                @foreach($formasenvio as $fe)

                                    <div class="radio">
                                        <label>
                                                <input type="radio" name="id_forma_envio" class="custom-radio" id="id_forma_envio" value="{{ $fe->id }}" {{ $c }}>&nbsp; {{ $fe->nombre_forma_envios.' , '.$fe->descripcion_forma_envios }}</label>
                                    </div>


                                 <?php $c=""; ?>  

                                @endforeach 
                                
                            </div> <!-- End form group -->
                            
                        </div> <!-- End Col -->

                       

                    

                    @else

                  

                        <div class="col-sm-10 col-sm-offset-1">

                            <h3>No hay Formas de envios para este grupo de usuarios</h3>

                        </div>  

                  

                    @endif  <!-- End formas de pago -->


                    <!-- Empiezo formas de pagp -->


                    @if(count($formaspago))

                   

                        <div class="col-sm-10 col-sm-offset-1">

                             <div class="form-group col-sm-10 col-sm-offset-1 ">

                                <h3>    Formas de pago</h3>

                                 <?php $c="checked"; ?>     

                                 <!-- Se construyen las opciones de envios -->                   

                                @foreach($formaspago as $fp)

                                    <div class="radio">
                                        <label>
                                                <input type="radio" name="id_forma_pago" class="custom-radio" id="id_forma_pago" value="{{ $fp->id }}" {{ $c }}>&nbsp; {{ $fp->nombre_forma_pago.' , '.$fp->descripcion_forma_pago }}</label>
                                    </div>


                                 <?php $c=""; ?>  

                                @endforeach 


                                

                                
                            </div>
                            
                        </div>
                    



                    @else

                    <div class="row">
                        <div class="col-sm-12">
                            <h3>No hay Formas de envios para este grupo de usuarios</h3>
                        </div>  

                    </div>

                    @endif  

                    <!-- End formas de pago -->





                @else

                    <div class="col-sm-12">
                        
                        <h3>Debe agregar una direccion de envio  </h3>

                        <button class="btn btn-raised btn-primary md-trigger addDireccionModal" >Agregar Nueva Direccion </button>
                    
                    </div>
                

                @endif 

                </div>

                
            </div>


        <br>    
     </div>

     @else


     <h1><span class="label label-primary">No hay productos en el carro</span></h1>
        

     @endif

     <hr>
     <p style="text-align: center;">

         <a class="btn btn-default" href="{{url('/productos')}}">Cancelar </a>

         <button class="btn btn-primary" type="submit"> Enviar Pedido  </button>

     </p>

     {!! Form::close() !!}
     
</div>

<!-- Modal Direccion -->
 <div class="modal fade" id="addDireccionModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Direccion</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('cart/storedir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Nickname Direccion</label>

                                    <div class="col-sm-8">
                                        <input id="nickname_address" name="nickname_address" type="text" placeholder="Nickname Direccion" class="form-control">
                                    </div>
                                </div>

                               


                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Pais
                                    </label>
                                    <div class="col-md-8" >
                                        <select id="country_id" name="country_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            @foreach($countries as $pais)
                                            <option value="{{ $pais->id }}"
                                                    @if($pais->id == old('country_id')) selected="selected" @endif >{{ $pais->country_name}}</option>
                                            @endforeach
                                          
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Estado
                                    </label>
                                    <div class="col-md-8" >
                                        <select id="state_id" name="state_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            
                                          
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Ciudad
                                    </label>
                                    <div class="col-md-8" >
                                        <select id="city_id" name="city_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            
                                          
                                        </select>
                                    </div>
                                </div>



                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Calle </label>

                                    <div class="col-sm-8">
                                        <input id="calle_address" name="calle_address" type="text" placeholder="Calle" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Calle </label>

                                    <div class="col-sm-8">
                                        <input id="calle2_address" name="calle2_address" type="text" placeholder="" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Postal</label>

                                    <div class="col-sm-8">
                                        <input id="codigo_postal_address" name="codigo_postal_address" type="text" placeholder="Codigo Postal" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Telefono</label>

                                    <div class="col-sm-8">
                                        <input id="telefono_address" name="telefono_address" type="text" placeholder="Telefono" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Notas</label>

                                    <div class="col-sm-8">
                                        <textarea id="notas" name="notas" type="text" placeholder="Notas" class="form-control"></textarea>
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendDireccion" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->



@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>





    <script>



        jQuery(document).ready(function () {
            new WOW().init();
        });

        $('.addDireccionModal').on('click', function(){
            $("#addDireccionModal").modal('show');
        })


    </script>

    <!-- modal js -->

    <script type="text/javascript" src="{{ asset('assets/vendors/modal/js/classie.js')}}"></script>
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

        city_id: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar una ciudad'
                }
            }
        }
    }
});



$('.sendDireccion').click(function () {
    
    var $validator = $('#addDireccionForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        nickname_address=$("#nickname_address").val();
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


        //document.getElementById("addDireccionForm").submit();


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