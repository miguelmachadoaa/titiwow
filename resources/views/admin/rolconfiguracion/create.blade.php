@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Rol - Configuracion
    @parent
@stop

@section('header_styles')

    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />

@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Rol - Configuracion
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Configuraciones</li>
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
                       Configuracion para cada rol 
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
                    <form class="form-horizontal" role="form" method="post" action="{{ route('admin.rolconfiguracion.store') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="row">
                            
                            <div class="col-sm-12">
                                
                          

                        @foreach ($roles as $rol)

                            <div class="form-group">

                            <label>Rol {{ $rol->name }}</label>


                                             
                                
                                   

                                    

                                    <div class="checkbox">
                                        <label>
                                            <input 
                                            name="{{ $rol->id.'-'.'precio' }}" 
                                            id="{{ $rol->id.'-'.'precio' }}" 
                                            type="checkbox" 
                                            class="custom-checkbox @if ($data[$rol->id]['precio']==1) checked @endif " 
                                            value="{{ $rol->id.'-'.'precio' }}"
                                            @if ($data[$rol->id]['precio']==1) checked @endif
                                            >
                                            &nbsp; Precio

                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input 
                                            name="{{ $rol->id.'-'.'referido' }}" 
                                            id="{{ $rol->id.'-'.'referido' }}" 
                                            type="checkbox" 
                                            class="custom-checkbox @if ($data[$rol->id]['referido']==1) checked @endif " 
                                            value="{{ $rol->id.'-'.'referido' }}"
                                            @if ($data[$rol->id]['referido']==1) checked @endif
                                            >
                                            &nbsp; Referido

                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input 
                                            name="{{ $rol->id.'-'.'empresa' }}" 
                                            id="{{ $rol->id.'-'.'empresa' }}" 
                                            type="checkbox" 
                                            class="custom-checkbox @if ($data[$rol->id]['empresa'])==1 checked @endif " 
                                            value="{{ $rol->id.'-'.'empresa' }}"
                                            @if ($data[$rol->id]['empresa']==1) checked @endif
                                            >
                                            &nbsp; Empresa

                                        </label>
                                    </div>

                                    

                        
                            

                            </div>
                        
                        @endforeach

                          </div>
                        </div>
                       





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.rolconfiguracion.index') }}">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Guardar
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

    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" ></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form_examples.js') }}"></script>

@stop