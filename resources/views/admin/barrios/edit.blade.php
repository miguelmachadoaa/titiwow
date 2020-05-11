@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Barrios
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
        Editar Barrios
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Barrio</li>
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
                       Editar Barrio
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($barrio, ['url' => secure_url('admin/barrios/'. $barrio->id), 'method' => 'put', 'class' => 'form-horizontal', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <input type="hidden" name="id_barrio" id="id_barrio" value="{{$barrio->id}}">
                          
                        <div class="form-group {{ $errors->
                            first('state_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Departamento
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="state_id" name="state_id" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    
                                    @foreach($states as $state)

                                    <option   @if($state->id==$barrio->state_id) {{'Selected'}} @endif   value="{{ $state->id }}">   {{ $state->state_name}}</option>
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

                                    @foreach($cities as $city)

                                    <option   @if($city->id==$barrio->city_id) {{'Selected'}} @endif   value="{{ $city->id }}">   {{ $city->city_name}}</option>
                                    @endforeach
                                  
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
                                <input type="text" id="barrio_name" name="barrio_name" class="form-control" placeholder="Nombre de Barrio"  value="{!! old('barrio_name'), $barrio->barrio_name !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('barrio_name', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>







                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.empresas.index') }}">
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

@stop
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

@stop
