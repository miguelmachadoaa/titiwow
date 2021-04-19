@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Abono de Cliente
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>@lang('clientes/title.edit')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('general.dashboard')
                </a>
            </li>
            <li><a href="#"> @lang('clientes/title.clientes')</a></li>
            <li class="active">Abono de Cliente</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            @lang('clientes/title.edit')
                        </h3>
                        <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        {!! Form::model($user, ['url' => secure_url('admin/clientes/'. $user->id.'abono'), 'method' => 'post', 'class' => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                            {{ csrf_field() }}

                            <input type="hidden" name="id_cliente" id="id_cliente" value="{{ $user->id }}">

                            <div class="form-group {{ $errors->
                            first('codigo_abono', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Ingrese Codigo de Abono
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="codigo_abono" name="codigo_abono" class="form-control" placeholder="Codigo Abono"
                                           value="{!! old('codigo_abono', $abono->codigo_abono) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('codigo_abono', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            
                        </form>


                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
    <script src="{{ secure_asset('assets/js/pages/editclientes.js') }}"></script>

@stop

