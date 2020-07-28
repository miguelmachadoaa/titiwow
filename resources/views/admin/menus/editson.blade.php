@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Sub Menu
@parent
@stop


{{-- page level styles --}}
@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
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
        Editar Sub Menu
@parent
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Menu</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Sub Menu
                    </h4>
                </div>

                <div class="panel-body">
                    
                        {!! Form::model($detalle, ['url' => secure_url('admin/menus/'. $detalle->id.'/updson'), 'files'=> true, 'method' => 'post', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <!--input type="hidden" name="parent" id="parent" value="{{$detalle->parent}}"-->
                          
                            <div class="form-group {{ $errors->
                            first('name', 'has-error') }}">
                            
                            <label for="title" class="col-sm-2 control-label">Nombre </label>

                            <div class="col-sm-5">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nombre de Categoria" value="{!! old('name', $detalle->name) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->first('tipo_enlace', 'has-error') }}">
                            <label for="select21" class="col-sm-2 control-label">
                                Tipo de Enlace
                            </label>
                            <div class="col-sm-5">   
                             <select id="tipo_enlace" name="tipo_enlace" class="form-control ">
                                <option value="">Seleccione</option>
                                    
                                   
                                    <option value="{{ 1 }}" >Paginas</option>

                                    <option value="{{ 2}}" >Producto</option>

                                    <option value="{{ 3 }}" >Categoria</option>

                                    <option value="{{ 4}}" >Marca</option>

                                    <option value="{{ 5}}" >Url</option>
                                   
                            </select>
                            <div class="col-sm-4">
                                {!! $errors->first('tipo_enlace', '<span class="help-block">:message</span> ') !!}
                            </div>
                              
                            </div>
                           
                        </div>


                        <div class="form-group  {{ $errors->first('elemento_enlace', 'has-error') }}">
                            <label for="select21" class="col-sm-2 control-label">
                                Elemento Enlace
                            </label>
                            <div class="col-sm-5">   
                             <select id="elemento_enlace" name="elemento_enlace" class="form-control ">
                                <option value="">Seleccione</option>
                                   
                            </select>
                            <div class="col-sm-4">
                                {!! $errors->first('elemento_enlace', '<span class="help-block">:message</span> ') !!}
                            </div>
                              
                            </div>
                           
                        </div>

                        <div class="form-group {{ $errors->first('slug', 'has-error') }}">

                            <label for="title" class="col-sm-2 control-label"> Slug </label>
                            <div class="col-sm-5">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug Sub Menu" value="{!! old('slug', $detalle->slug) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('slug', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                         <div class="form-group {{ $errors->first('order', 'has-error') }}">

                            <label for="title" class="col-sm-2 control-label"> order </label>
                            <div class="col-sm-5">
                                <input type="text" id="order" name="order" class="form-control" placeholder="Orden Sub Menu" value="{!! old('order', $detalle->order) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('order', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('parent', 'has-error') }}">

                            <label for="title" class="col-sm-2 control-label"> parent </label>
                            <div class="col-sm-5">
                                <input type="text" id="parent" name="parent" class="form-control" placeholder="Orden Sub Menu" value="{!! old('parent', $detalle->parent) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('parent', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ secure_url('admin/menus') }}">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>


<input type="hidden" name="base" id="base" value="{{secure_url('/')}}">

@stop



{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>


<script>
    

     $('select[name="tipo_enlace"]').on('change', function() {
            var stateID = $(this).val();
            var base = $('#base').val();

            if(stateID) {
                $.ajax({
                    url: base+'/configuracion/tipourl/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        
                        $('select[name="elemento_enlace"]').empty();

                        $.each(data, function(key, value) {
                            $('select[name="elemento_enlace"]').append('<option value="'+ key+'">'+ value +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="elemento_enlace"]').empty();
            }
        });



      $('select[name="elemento_enlace"]').on('change', function() {

            var stateID = $(this).val();

            var base = $('#base').val();

            $('#slug').val($(this).val());

            
        });


</script>

@stop

