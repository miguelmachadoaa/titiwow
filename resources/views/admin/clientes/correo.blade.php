@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Cliente 
@parent
@stop


{{-- page level styles --}}
@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/timeline.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/timeline2.css') }}" rel="stylesheet" />
    
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Enviar Correo 
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Clientes</li>
        <li class="active">Ver</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">


     <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Enviar correo
                                </h3>
                                
                            </div>
                            <div class="panel-body">


                       

                               
                            </div>
                        </div>
                    </div>
                </div>


    


 

        <br>

    



                <div class="row">
                
            <p style="text-align: center;"> 
                    <a class="btn btn-default" href="{{ secure_url('admin/clientes') }}">Volver</a>

            </p>

        </div>

        <input type="hidden" name="base" id="base" value="{{ secure_url('/')}}">


</section>


<!-- Main content -->

@stop


{{-- page level scripts --}}
@section('footer_scripts')



    <!-- Modal Direccion -->
    <div class="modal fade" id="rolModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar </h4>
                        
                </div>
                
                <div class="modal-body " style="min-height: 10em;">
                    <input type="hidden" name="usuario_id" id="usuario_id" value="">
                     
                    <h3>Esta seguro de que desea asignar el usuario a <b>Invitados Alpina</b>, tenga en cuenta que si este usuario tiene amigos, ellos tambien se asignaran a Invitados Alpina.</h3>

                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button"   class="btn  btn-primary saveInvitacion" >Aceptar</button>
                    
                </div>
            </div>
        </div>
    </div>



<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>



    <script>
        $(document).ready(function(){

            $('.changerol').on('click', function(){

                $('#usuario_id').val($(this).data('id'));
                $('#rolModal').modal('show');
            });


            $('.saveInvitacion').on('click', function(){

                cliente=$('#usuario_id').val();
                base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{  cliente },
                    url: base+"/admin/clientes/updaterol",
                        
                    complete: function(datos){     

                        if (datos.responseText=='true') {

                            location.reload();

                        }else{

                            alert('Hubo un error al actualizar intente nuevamente');
                        }

                    }
                });

            });



        });
    </script>

@stop
