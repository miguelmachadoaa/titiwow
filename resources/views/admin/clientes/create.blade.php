@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('clientes/title.add')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>@lang('clientes/title.add')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('general.dashboard')
                </a>
            </li>
            <li><a href="#"> @lang('clientes/title.clientes')</a></li>
            <li class="active">@lang('clientes/title.add')</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            @lang('clientes/title.add')
                        </h3>
                        <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <form id="commentForm" action="{{ route('admin.users.store') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div id="rootwizard">
                                <ul>
                                    <li><a href="#tab1" data-toggle="tab">@lang('clientes/title.tab1')</a></li>
                                    <li><a href="#tab2" data-toggle="tab">@lang('clientes/title.tab2')</a></li>
                                    <li><a href="#tab4" data-toggle="tab">@lang('clientes/title.tab3')</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab1">
                                        <h2 class="hidden">&nbsp;</h2>
                                        <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                                            <label for="first_name" class="col-sm-2 control-label">First Name *</label>
                                            <div class="col-sm-10">
                                                <input id="first_name" name="first_name" type="text"
                                                       placeholder="First Name" class="form-control required"
                                                       value="{!! old('first_name') !!}"/>

                                                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                            <label for="last_name" class="col-sm-2 control-label">Last Name *</label>
                                            <div class="col-sm-10">
                                                <input id="last_name" name="last_name" type="text" placeholder="Last Name"
                                                       class="form-control required" value="{!! old('last_name') !!}"/>

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                            <label for="email" class="col-sm-2 control-label">Email *</label>
                                            <div class="col-sm-10">
                                                <input id="email" name="email" placeholder="E-mail" type="text"
                                                       class="form-control required email" value="{!! old('email') !!}"/>
                                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                            <label for="password" class="col-sm-2 control-label">Password *</label>
                                            <div class="col-sm-10">
                                                <input id="password" name="password" type="password" placeholder="Password"
                                                       class="form-control required" value="{!! old('password') !!}"/>
                                                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                            <label for="password_confirm" class="col-sm-2 control-label">Confirm Password *</label>
                                            <div class="col-sm-10">
                                                <input id="password_confirm" name="password_confirm" type="password"
                                                       placeholder="Confirm Password " class="form-control required"/>
                                                {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab2" disabled="disabled">
                                        <h2 class="hidden">&nbsp;</h2> <div class="form-group  {{ $errors->first('dob', 'has-error') }}">
                                            <label for="dob" class="col-sm-2 control-label">Fecha de Nacimiento</label>
                                            <div class="col-sm-10">
                                                <input id="dob" name="dob" type="text" class="form-control"
                                                       data-date-format="YYYY-MM-DD"
                                                       placeholder="yyyy-mm-dd"/>
                                            </div>
                                            <span class="help-block">{{ $errors->first('dob', ':message') }}</span>
                                        </div>

                                        <div class="form-group required">
                                            <label for="genero_cliente" class="col-sm-2 control-label">Genero</label>
                                            <div class="col-sm-10">
                                                <select class="form-control required" title="Selecciona Genero" name="genero_cliente" id="genero_cliente">
                                                    <option value="">Seleccionar</option>
                                                    <option value="1">Femenino</option>
                                                    <option value="2">Masculino</option>
                                                </select>
                                                {!! $errors->first('genero_cliente', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('genero_cliente', ':message') }}</span>
                                        </div>

                                        <div class="form-group required">
                                            <label for="id_type_doc" class="col-sm-2 control-label">Tipo de Documento *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control required" title="Selecciona Tipo de Documento..." name="id_type_doc"
                                                        id="id_type_doc">
                                                    <option value="">Selecciona el Tipo de Documento *</option>
                                                    @foreach($tdocumento as $tdocument)
                                                        <option value="{{ $tdocument->id }}"
                                                                @if($tdocument->id == old('id_type_doc')) selected="selected" @endif >{{ $tdocument->nombre_tipo_documento }} - {{ $tdocument->abrev_tipo_documento }}</option>
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('id_type_doc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('group', ':message') }}</span>
                                        </div>

                                        <div class="form-group {{ $errors->first('doc_cliente', 'has-error') }}">
                                            <label for="doc_cliente" class="col-sm-2 control-label">Documento *</label>
                                            <div class="col-sm-10">
                                                <input id="doc_cliente" name="doc_cliente" type="text"
                                                       placeholder="Número de Documento" class="form-control required"
                                                       value="{!! old('doc_cliente') !!}"/>

                                                {!! $errors->first('doc_cliente', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('telefono_cliente', 'has-error') }}">
                                            <label for="telefono_cliente" class="col-sm-2 control-label">Número Telefónico *</label>
                                            <div class="col-sm-10">
                                                <input id="telefono_cliente" name="telefono_cliente" type="number"
                                                       placeholder="Número Telefónico" class="form-control required"
                                                       value="{!! old('telefono_cliente') !!}"/>

                                                {!! $errors->first('doc_cliente', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('pic_file', 'has-error') }}">
                                            <label for="pic" class="col-sm-2 control-label">Foto de Perfil</label>
                                            <div class="col-sm-10">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                        <img src="http://placehold.it/200x200" alt="profile pic">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                    <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Selecionar Foto</span>
                                                    <span class="fileinput-exists">Cambiar</span>
                                                    <input id="pic" name="pic_file" type="file" class="form-control"/>
                                                </span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                           data-dismiss="fileinput">Eliminar</a>
                                                    </div>
                                                </div>
                                                <span class="help-block">{{ $errors->first('pic_file', ':message') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="marketing_cliente" class="col-sm-2 control-label"> Marketing</label>
                                            <div class="col-sm-10">
                                                <input id="marketing_cliente" name="marketing_cliente" type="checkbox"
                                                       class="pos-rel p-l-30 custom-checkbox "
                                                       value="1" @if(old('marketing_cliente')) checked="checked" @endif >
                                                <span>¿Acepta recibir campañas de marketing en su email?</span></div>

                                        </div>

                                        <div class="form-group">
                                            <label for="habeas_cliente" class="col-sm-2 control-label"> Habeas Data *</label>
                                            <div class="col-sm-10">
                                                <input id="habeas_cliente" name="habeas_cliente" type="checkbox"
                                                       class="pos-rel p-l-30 custom-checkbox required"
                                                       value="1" @if(old('habeas_cliente')) checked="checked" @endif >
                                                <span>¿Acepata nuestra politica de tratamiento de datos?</span></div>

                                        </div>

                                    </div>
                                    

                                    <div class="tab-pane" id="tab4" disabled="disabled">
                                        <p class="text-danger"><strong>Be careful with group selection, if you give admin access.. they can access admin section</strong></p>

                                        <div class="form-group required">
                                            <label for="group" class="col-sm-2 control-label">Group *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control required" title="Select group..." name="group"
                                                        id="group">
                                                    <option value="">Select</option>
                                                    @foreach($groups as $group)
                                                        <option value="{{ $group->id }}"
                                                                @if($group->id == old('group')) selected="selected" @endif >{{ $group->name}}</option>
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('group', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('group', ':message') }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="activate" class="col-sm-2 control-label"> Activate User *</label>
                                            <div class="col-sm-10">
                                                <input id="activate" name="activate" type="checkbox"
                                                       class="pos-rel p-l-30 custom-checkbox"
                                                       value="1" @if(old('activate')) checked="checked" @endif >
                                                <span>To activate user account automatically, click the check box</span></div>

                                        </div>
                                    </div>
                                    <ul class="pager wizard">
                                        <li class="previous"><a href="#">@lang('clientes/title.previous')</a></li>
                                        <li class="next"><a href="#">@lang('clientes/title.next')</a></li>
                                        <li class="next finish" style="display:none;"><a href="javascript:;">Finish</a></li>
                                    </ul>
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
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script>
        "use strict";
        // bootstrap wizard//
        $("#genero_cliente, #genero_cliente1").select2({
            theme:"bootstrap",
            placeholder:"",
            width: '100%'
        });
        $('input[type="checkbox"].custom-checkbox, input[type="radio"].custom-radio').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%'
        });
        $("#dob").datetimepicker({
            format: 'YYYY-MM-DD',
            widgetPositioning:{
                vertical:'bottom'
            },
            keepOpen:false,
            useCurrent: false,
            maxDate: moment().add(1,'h').toDate()
        });
        $("#commentForm").bootstrapValidator({
            fields: {
                first_name: {
                    validators: {
                        notEmpty: {
                            message: 'The first name is required'
                        }
                    },
                    required: true,
                    minlength: 3
                },
                last_name: {
                    validators: {
                        notEmpty: {
                            message: 'The last name is required'
                        }
                    },
                    required: true,
                    minlength: 3
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'Password is required'
                        },
                        different: {
                            field: 'first_name,last_name',
                            message: 'Password should not match first name'
                        }
                    }
                },
                password_confirm: {
                    validators: {
                        notEmpty: {
                            message: 'Confirm Password is required'
                        },
                        identical: {
                            field: 'password'
                        },
                        different: {
                            field: 'first_name,last_name',
                            message: 'Confirm Password should match with password'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address is required'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
                        }
                    }
                },
                bio: {
                    validators: {
                        notEmpty: {
                            message: 'Bio is required and cannot be empty'
                        }
                    },
                    minlength: 20
                },

                gender: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a gender'
                        }
                    }
                },

                group: {
                    validators:{
                        notEmpty:{
                            message: 'You must select a group'
                        }
                    }
                }
            }
        });

        $('#rootwizard').bootstrapWizard({
            'tabClass': 'nav nav-pills',
            'onNext': function(tab, navigation, index) {
                var $validator = $('#commentForm').data('bootstrapValidator').validate();
                return $validator.isValid();
            },
            onTabClick: function(tab, navigation, index) {
                return false;
            },
            onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index + 1;

                // If it's the last tab then hide the last button and show the finish instead
                if ($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});

        $('#rootwizard .finish').click(function () {
            var $validator = $('#commentForm').data('bootstrapValidator').validate();
            if ($validator.isValid()) {
                document.getElementById("commentForm").submit();
            }

        });
        // $('#activate').on('ifChanged', function(event){
        //     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
        // });
        $('#commentForm').keypress(
            function(event){
                if (event.which == '13') {
                    event.preventDefault();
                }
            });
    </script>
@stop
