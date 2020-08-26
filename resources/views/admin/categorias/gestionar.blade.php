@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Categorias Gestión
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Categorias Gestión</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Categorias Gestión</a></li>
        <li class="active">Listado</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Categorias Gestión
                    </h4>
                    <div class="pull-right">
                    
                    </div>
                </div>
                <br />
                <div class="panel-body">

                        <input type="hidden" name="id_categoria" id="id_categoria" value="{{ $categoria->id }}" />

                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />

                        <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

                        <fieldset>
                        <legend>Productos Destacados por almacen para esta categoria</legend>

                        <div class="row">
                            
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_almacen', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Almacenes
                                </label>
                                <div class="col-sm-4">   
                                 <select id="id_almacen" name="id_almacen" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($almacenes as $alm)

                                        <option value="{{$alm->id}}">{{$alm->nombre_almacen}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_almacen', '<span class="help-block">:message</span> ') !!}
                                </div>


                                 <div class="col-sm-4">   
                                 <select id="id_producto" name="id_producto" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                   

                                </select>

                                  {!! $errors->first('id_producto', '<span class="help-block">:message</span> ') !!}
                                </div>


                           
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" role="button" class="btn btn-success addAlmacenCupon" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listdestacado">

                            @include('admin.categorias.listdestacado')
                            


                        </div>
                    </fieldset>

            
                  
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <a class="btn btn-primary" href="{{secure_url('admin/categorias')}}">Volver</a>
        </div>
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<div class="modal fade" id="users_exists" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                @lang('groups/message.users_exists')
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

<script>

$(".select2").select2();

    $('select[name="id_almacen"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();
                var id_categoria = $('#id_categoria').val();
                var id_almacen = $('#id_almacen').val();

                    if(id_categoria) {
                        $.ajax({
                            url: base+'/admin/categorias/almacen/'+id_almacen+'/categoria/'+id_categoria+'/getproductos',
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="id_producto"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="id_producto"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });





      $('.addAlmacenCupon').on('click', function(){

        base = $('#base').val();

        id_categoria = $('#id_categoria').val();

        id_producto = $('#id_producto').val();

        id_almacen = $('#id_almacen').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_categoria, id_producto, id_almacen},
            url: base+"/admin/categorias/"+id_categoria+"/adddestacado",
                
            complete: function(datos){     

                $(".listdestacado").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delCategoriaDestacada',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_categoria = $(this).data('idcategoria');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_categoria, _token},
            url: base+"/admin/categorias/"+id+"/deldestacado",
                
            complete: function(datos){     

                $(".listdestacado").html(datos.responseText);

            }
        });

    });

   



   

    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});

    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });

</script>
@stop
