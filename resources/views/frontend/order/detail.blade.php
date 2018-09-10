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
<div class="container text-center">
    <div class="row">
        <h1>Carro de Compras</h1>
        @if(count($cart))
            

            <br>    

            <h3>    Detalle de Cliente</h3>

            <div class="col-md-10 col-md-offset-1 table-responsive">
         <table class="table table-striped ">
                 <thead>
                     <tr>
                         <th>ID</th>
                         <th>Nombre</th>
                         <th>Apellido</th>
                         <th>Email</th>
                         <th>Direccion</th>
                       
                     </tr>
                 </thead>
                 <tbody>
                    
                        <tr>
                            <td>{{Sentinel::getUser()->id}}</td>
                            <td>{{Sentinel::getUser()->first_name}}</td>
                            <td>{{Sentinel::getUser()->last_name}}</td>
                            <td>{{Sentinel::getUser()->email}}</td>
                            <td>{{Sentinel::getUser()->address}}</td>
                           
                        </tr>
                    

                 </tbody>
             </table>

             <hr>

             
         </div>

       
        <br>
            <div class="row">   
                <div class="col-sm-12">  
                        <h3>    Detalle de Pedido</h3>
                 </div>
                
            </div>

        <br>    

         <div class="col-md-10 col-md-offset-1 table-responsive">
	     <table class="table table-striped ">
                 <thead>
                     <tr>
                         <th>Imagen</th>
                         <th>Producto</th>
                         <th>Precio</th>
                         <th>Cantidad</th>
                         <th>SubTotal</th>
                         <th>Eliminar</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($cart as $row)
                        <tr>
                            <td><img height="60px" src="../uploads/productos/{{$row->imagen_producto}}"></td>
                            <td>{{$row->nombre_producto}}</td>
                            <td>{{number_format($row->precio,2)}}</td>
                            <td>
                                {{ $row->cantidad }}

                            </td>
                            <td>{{ number_format($row->cantidad*$row->precio, 2) }}</td>
                            <td><a class="btn btn-danger" href="{{url('cart/delete', [$row->slug])}}">X</a></td>
                        </tr>
                     @endforeach

                 </tbody>
             </table>

             <hr>

             <h3><span class="label label-success">Total: {{number_format($total, 2)}}</span></h3>
         </div>

         <br>
            <div class="row">   
                <div class="col-sm-12">  
                        <h3>    Direccion de Envio </h3>
                 </div>

                 <div class="row">

                @if(count($direcciones))
                

                    @foreach($direcciones as $direccion)

                    
                    <div class="col-sm-3">
                        
                         <div class="well <?php if ($direccion->default_address=='1'){ echo 'bg-primary';}else{ echo 'bg-default';} ?>" >
                             
                        
                            <p>
                                <b>{{$direccion->nickname_address}}</b><br>
                                Direccion: {{$direccion->calle_address.' '.$direccion->calle2_address}}<br>
                                Codigo Postal: {{$direccion->codigo_postal_address}}<br>
                                Telefono: {{$direccion->telefono_address}}<br>
                                Ciudad: {{$direccion->city_id}}<br>

                            </p>

                            <hr>

                            <p>
                                @if($direccion->default_address!='1')

                                   <a class="btn btn-xs btn-default" href="{{url('cart/setdir/'.$direccion->id)}}">Establecer por defecto</a>

                                @endif 

                             

                                <a class="btn btn-xs btn-danger" href="{{url('cart/deldir/'.$direccion->id)}}">Eliminar</a>

                            </p>



                        </div>
                    </div>
                            
                    @endforeach 

                    <div class="col-sm-12">

                        <button class="btn btn-raised btn-primary md-trigger addDireccionModal" data-toggle="modal" data-target="#modal-21">Agregar Nueva Direccion </button>

                    </div>


                    <hr>


                    @if(count($formasenvio))

                    <div class="row">
                        <div class="col-sm-12">

                             <div class="form-group">

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


                                

                                
                            </div>
                            
                        </div>
                    </div>

                    @else

                    <div class="row">
                        <div class="col-sm-12">
                            <h3>No hay Formas de envios para seste grupo de usuarios</h3>
                        </div>  

                    </div>

                    @endif  <!-- End formas de pago -->


                    <!-- Empiezo formas de pagp -->


                    @if(count($formaspago))

                    <div class="row">
                        <div class="col-sm-12">

                             <div class="form-group">

                                <h3>    Formas de Pago</h3>

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">


                                

                                 <!-- Se construyen las opciones de envios -->      

                                 <?php  $e='true'; ?>             
                                 <?php  $c='in'; ?>             

                                @foreach($formaspago as $fp)

                                    


                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading{{ $fp->id }}">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $fp->id }}" aria-expanded="{{ $e }}" aria-controls="collapseOne">
                                                <h4 class="panel-title">{{$fp->nombre_forma_pago}}</h4>
                                            </a>
                                        </div>
                                        <div id="collapse{{ $fp->id }}" class="panel-collapse collapse {{ $c }}" role="tabpanel" aria-labelledby="heading{{ $fp->id }}">
                                            <div class="panel-body">
                                                {{$fp->descripcion_forma_pago}}
                                            </div>
                                        </div>
                                    </div>
                                   
                                    
                                
                                    <?php  $e='false'; ?> 
                                    <?php  $c=''; ?> 
                               

                                @endforeach 

                                </div>
                                

                                
                            </div>
                            
                        </div>
                    </div>

                    @else

                    <div class="row">
                        <div class="col-sm-12">
                            <h3>No hay Formas de envios para seste grupo de usuarios</h3>
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
     <p>
         <a class="btn btn-primary" href="{{url('#')}}">Pagar </a>
         <a class="btn btn-primary" href="{{url('#')}}">Continuar</a>
     </p>
     
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

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Nickname Direccion</label>

                                    <div class="col-sm-8">
                                        <input id="nickname_address" name="nickname_address" type="text" placeholder="Nickname Direccion" class="form-control">
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



@stop


@section('footer_scripts')
    
@stop