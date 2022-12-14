@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Barrio
    @parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Barrio
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Barrio</li>
        <li class="active">
            Crear
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Crear Barrio
                    </h4>
                </div>
                <div class="panel-body">
                    {!! $errors->first('slug', '<span class="help-block">Another role with same slug exists, please choose another name</span> ') !!}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form enctype="multipart/form-data" class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/barrios/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                          <div class="form-group {{ $errors->
                            first('state_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Departamento
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="state_id" name="state_id" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    
                                    @foreach($states as $state)

                                    <option value="{{ $state->id }}">
                                            {{ $state->state_name}}</option>
                                    @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('state_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('city_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Ciudad
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="city_id" name="city_id" class="form-control select2">
                                    <option value="">Seleccione</option>
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('city_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('barrio_name', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre  Barrio
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="barrio_name" name="barrio_name" class="form-control" placeholder="Nombre de Barrio"  value="{!! old('barrio_name') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('barrio_name', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                               
                                <a class="btn btn-danger" href="{{ secure_url('admin/barrios') }}">
                                    Cancelar
                                </a>

                                 <button type="submit" class="btn btn-success">
                                    Crear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row-->


    <input type="hidden" name="base" id="base" value="{{secure_url('/')}}">
</section>
@stop
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>




<script>
    


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
                            $('select[name="city_id"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="city_id"]').empty();
            }
        });








</script>

@stop
