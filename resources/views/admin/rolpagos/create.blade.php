@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Rol - Formas de Pago
    @parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />

@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Rol - Formas de Pago
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Formas de Pago</li>
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
                       Formas de pago para cada rol 
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
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/rolpagos/create') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="row">
                            
                            <div class="col-sm-12">
                                
                          

                        @foreach ($roles as $rol)

                            <div class="form-group">

                            <label>Rol {{ $rol->name }}</label>


                                @foreach ($formas as $forma)
                                
                                    <?php $c=''; ?>

                                    <?php if( isset($data[$rol->id][$forma->id]) ){$c='checked';} ?>

                                    <div class="checkbox">
                                        <label>
                                            <input 
                                            name="{{ $rol->id.'-'.$forma->id }}" 
                                            id="{{ $rol->id.'-'.$forma->id }}" 
                                            type="checkbox" 
                                            class="custom-checkbox {{ $c }}" 
                                            value="{{ $rol->id.'-'.$forma->id }}"
                                            {{ $c }}
                                            >
                                            &nbsp; {{ $forma->nombre_forma_pago }}

                                        </label>
                                    </div>

                        
                                @endforeach

                            </div>
                        
                        @endforeach

                          </div>
                        </div>
                       





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                               
                                <a class="btn btn-danger" href="{{ secure_url('admin/rolpagos/') }}">
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

    <script src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" ></script>
    <script src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ secure_asset('assets/js/pages/form_examples.js') }}"></script>

@stop