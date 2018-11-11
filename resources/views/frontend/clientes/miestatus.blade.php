
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mi Estatus  
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('clientes') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('miestatus') }}">Mi Estatus  </a>
                </li>
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">


    <div class="row">
         @if(isset($puntos['nivel']))

            <div class="alert alert-default">

                <div class="row">
                    
                    <div class="col-sm-3">
                        <img class="img img-responsive" src="{{ url('/').'/assets/images/'.$puntos['nivel'].'.png' }}">
                    </div>
                    <div class="col-sm-9">
                        <h3>Usted acumula compras este mes por  {{ $puntos['puntos'] }} COP</h3>

                        <h3>Lo que lo ubica en el nivel {{ $puntos['nivel'] }} con una comision de {{ $puntos['porcentaje']*100 }}%</h3>

                        <h3>Usted acumula una comision ganada de  {{ $puntos['puntos']*$puntos['porcentaje'] }} COP</h3>
                        
                    </div>
                </div>
                
                



            </div>

        @endif
        </div>



<div class="welcome">
            <h3>Mi Estatus</h3>
        </div>
        <hr>
    <div class="products">





        <div class="row" id="table_amigos">

            

        @if(!$referidos->isEmpty())

             <table class="table table-responsive" id="tbAmigos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>

            @foreach($referidos as $referido)

                    <tr>
                        <td>
                            {{ $referido->first_name }}
                        </td>
                        <td>
                            {{ $referido->last_name }}
                        </td>
                        
                        <td>

                            @if($referido->puntos)

                            {{ $referido->puntos }}

                            @else

                            0

                            @endif

                           
                        </td>
                       

                        
                    </tr>
                
            @endforeach
            </tbody>
             </table>
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen Referidos aun.
            </div>
        @endif
        </div>
        
    </div>
</div>
<div class="container">
    <div class="form-group">
        <div class="col-lg-offset-5 col-lg-10" style="margin-bottom:20px;">
            <a class="btn btn-danger" type="button" href="{{ url('clientes') }}">Regresar</a>
        </div>
    </div>
</div>



<!-- Modal Direccion -->
 <div class="modal fade" id="delAmigoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Amigo</h4>
                    </div>
                    <div class="modal-body">
                      
                           <input type="hidden" name="url" id="url" value="{{ url('clientes/deleteamigo') }}">

                            <input type="hidden" name="del_id" id="del_id" value="">

                            <h3> Esta seguro de eliminar su Amigo?</h3>

                    </div>

                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-danger sendEliminar" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>



<div class="modal fade" id="addAmigoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Amigo</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('storeamigo')}}" id="addAmigoForm" name="addAmigoForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">

                            {{ csrf_field() }}

                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Nombre</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="nombre" name="nombre" type="text" placeholder="Nombre" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Apellido</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="apellido" name="apellido" type="text" placeholder="Apellido" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Email</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="email" name="email" type="text" placeholder="Email" class="form-control">
                                    </div>
                                </div>





                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendAmigo" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>


    <div class="modal fade" id="delAmigoAmigoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Amigo</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="del_id" id="del_id" value="" >
                        <input type="hidden" name="del_url" id="del_url" value="" >
                        
                        <h3> Esta Seguro de eliminar el registro?</h3>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary deleteAmigo" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>





<!-- Modal Direccion -->

@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
     <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('#tbAmigos').on('click', '.eliminarAmigo',  function(e){

            e.preventDefault();

            $('#del_id').val($(this).data('id'));

            $('#delAmigoModal').modal('show');

        });

        $('.sendEliminar').on('click',  function(e){

            e.preventDefault();

            id=$('#del_id').val();

            url=$('#url').val();

            $.post(url, {id}, function(data) {

                $('#table_amigos').html(data);

                $('#delAmigoModal').modal('hide');

                         
            });

        });



        $(document).on('click', '.addAmigo', function(e){
            e.preventDefault();

           // alert('cantidad:'+$('#cantidad').val()+' limite:'+$('#limite').val());

            if (parseInt($('#limite').val())<parseInt($('#cantidad').val())){

                $('.res').html('<div class="alert alert-danger">Usted alcanzo el limite de sus amigos</div>');

            }else{

                $("#addAmigoModal").modal('show');


            }


        });

        $('html').on('click', '.delAmigo', function(e){

            e.preventDefault();

            $('#del_id').val($(this).data('id'));
            $('#del_url').val($(this).data('url'));

                $("#delAmigoAmigoModal").modal('show');

        });





$("#addAmigoForm").bootstrapValidator({
    fields: {
        nombre: {
            validators: {
                notEmpty: {
                    message: 'Nombre es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        apellido: {
            validators: {
                notEmpty: {
                    message: 'Apellido es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Ingrese Un Email Valido'
                    }, 
                    notEmpty: {
                    message: 'Email es Requerido'
                    }
                }
            }
        
    }
});


$('.sendAmigo').click(function () {
    
    var $validator = $('#addAmigoForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        nombre=$("#nombre").val();
        apellido=$("#apellido").val();
        email=$("#email").val();
        
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{nombre, apellido, email},
            url: base+"/storeamigo",
                
            complete: function(datos){     

                $(".amigosList").html(datos.responseText);

                $('#addAmigoModal').modal('hide');

                $("#nombre").val('');

                $("#apellido").val('');

                $("#email").val('');

            
            }
        });


    }

});


$('.deleteAmigo').click(function () {
    
        id=$("#del_id").val();
        url=$("#del_url").val();
               
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{id},
            url: url,
                
            complete: function(datos){     

                $(".amigosList").html(datos.responseText);

                $('#delAmigoAmigoModal').modal('hide');
            
            }
        });

});






    </script>
@stop