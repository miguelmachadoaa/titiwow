@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Usuarios del Area
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Usuarios del Area
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Usuarios del Area</li>
        <li class="active">Gestionar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Gestionar Usuarios del Area
                    </h4>
                </div>
                <div class="panel-body">
                   

                       <form enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ secure_url('admin/departamento/') }}">
                            <!-- CSRF Token -->
                           
                            <div class="form-group {{ $errors->
                                first('id_usuario', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Seleccione el usuario 
                                </label>
                                <div class="col-sm-5">
                                    
                                    <select id="id_usuario" name="id_usuario" class="form-control select2">


                                        @foreach($usuarios as $u)

                                        <option      value="{{ $u->id }}">
                                                {{ $u->first_name.' '.$u->last_name}}</option>
                                        @endforeach
                                        
                                      
                                    </select>
                                </div>
                                
                            </div> 

                            <div class="form-group col-sm-5  sm-offset-2">
                                
                                <button type="button" class="btn btn-primary addusuario">Agregar</button>

                            </div>

                            {{ csrf_field() }}

                    </form>


                         @include('admin.departamentos.listausuarios')



                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-danger" href="{{secure_url('admin/departamentos')}}">Volver</a>
                        </div>
                    </div>

                      
                   
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

    <input type="hidden" name="id_departamento" id="id_departamento" value="{{$departamento->id}}">

    <!-- row-->
</section>

@stop
@section('footer_scripts')

<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script type="text/javascript" src="{{ secure_asset('assets/js/in-view.min.js') }}"></script>



<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

<script>

    $(document).ready(function(){



    base=$('#base').val();

    var table =$('#tableAlmacen').DataTable({
        "processing": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": base+'/admin/departamentousuario/data/{{$departamento->id}}'
        }
    });

    table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    });


$(".select2").select2();

    $('.addusuario').on('click', function(){

        base = $('#base').val();

        id_usuario = $('#id_usuario').val();

        id_departamento = $('#id_departamento').val();


        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_departamento, id_usuario, _token},
            url: base+"/admin/departamentos/addusuario",
                
            complete: function(datos){     

                table.ajax.reload();

                //$(".listaproductos").html(datos.responseText);

            }
        });

    });


        $('.listausuarios').on('click', '.delusuario', function(){

            base = $('#base').val();

            id = $(this).data('id');

            _token = $('#_token').val();


             $.ajax({
                type: "POST",
                data:{ id, _token},
                url: base+"/admin/departamentos/delusuario",
                    
                complete: function(datos){   

                table.ajax.reload();  

                   // $(".listaproductos").html(datos.responseText);

                }
            });

        });



    });



</script>


@stop