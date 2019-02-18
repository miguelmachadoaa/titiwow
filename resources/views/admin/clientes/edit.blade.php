@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('clientes/title.edit')
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
            <li class="active">@lang('clientes/title.edit')</li>
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
                        {!! Form::model($user, ['url' => secure_url('admin/clientes/'. $user->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                            {{ csrf_field() }}

                            <input type="hidden" name="id_cliente" id="id_cliente" value="{{ $user->id }}">

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
                                            <label for="first_name" class="col-sm-2 control-label">Nombre *</label>
                                            <div class="col-sm-10">
                                                <input id="first_name" name="first_name" type="text"
                                                       placeholder="Nombre" class="form-control required"
                                                       value="{!! old('first_name', $user->first_name) !!}"/>

                                                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                            <label for="last_name" class="col-sm-2 control-label">Apellido *</label>
                                            <div class="col-sm-10">
                                                <input id="last_name" name="last_name" type="text" placeholder="Apellido"
                                                       class="form-control required" value="{!! old('last_name', $user->last_name) !!}"/>

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                            <label for="email" class="col-sm-2 control-label">Email *</label>
                                            <div class="col-sm-10">
                                                <input id="email" name="email" placeholder="E-mail" type="text"
                                                       class="form-control required email" value="{!! old('email', $user->email) !!}" readonly/>
                                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                            <label for="password" class="col-sm-2 control-label">Contraseña *</label>
                                            <div class="col-sm-10">
                                                <input id="password" name="password" type="password" placeholder="Contraseña"
                                                       class="form-control required" value="{!! old('password') !!}"/>
                                                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                            <label for="password_confirm" class="col-sm-2 control-label">Confirmar Contraseña *</label>
                                            <div class="col-sm-10">
                                                <input id="password_confirm" name="password_confirm" type="password"
                                                       placeholder="Confirmar Contraseña " class="form-control required"/>
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
                                                       placeholder="yyyy-mm-dd" value="{!! old('dob', $user->dob) !!}"/>
                                            </div>
                                            <span class="help-block">{{ $errors->first('dob', ':message') }}</span>
                                        </div>

                                        <div class="form-group required">
                                            <label for="genero_cliente" class="col-sm-2 control-label">Genero</label>
                                            <div class="col-sm-10">
                                                <select class="form-control required" title="Selecciona Genero" name="genero_cliente" id="genero_cliente">
                                                    <option value="">Seleccionar</option>
                                                    @foreach($cliente as $clien)
                                                    <option value="1" @if( $clien->genero_cliente === 2) selected="selected" @endif >Femenino</option>
                                                    <option value="2" @if( $clien->genero_cliente === 2) selected="selected" @endif >Masculino</option>
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('genero_cliente', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('genero_cliente', ':message') }}</span>
                                        </div>

                                        <div class="form-group required">
                                            <label for="id_type_doc" class="col-sm-2 control-label">Tipo de Documento *</label>
                                            <div class="col-sm-10">
                                            {!! Form::select('id_type_doc', $tdocumento,$clien->id_type_doc,array('class'=>'form-control required'));!!}

                                                
                                                {!! $errors->first('id_type_doc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('group', ':message') }}</span>
                                        </div>

                                        <div class="form-group {{ $errors->first('doc_cliente', 'has-error') }}">
                                            <label for="doc_cliente" class="col-sm-2 control-label">Documento *</label>
                                            <div class="col-sm-10">
                                                <input id="doc_cliente" name="doc_cliente" type="text"
                                                       placeholder="Número de Documento" class="form-control required"
                                                       value="{!! old('doc_cliente', $clien->doc_cliente) !!}"/>

                                                {!! $errors->first('doc_cliente', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('telefono_cliente', 'has-error') }}">
                                            <label for="telefono_cliente" class="col-sm-2 control-label">Número Telefónico *</label>
                                            <div class="col-sm-10">
                                                <input id="telefono_cliente" name="telefono_cliente" type="number"
                                                       placeholder="Número Telefónico" class="form-control required"
                                                       value="{!! old('doc_cliente', $clien->doc_cliente) !!}"/>

                                                {!! $errors->first('telefono_cliente', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('pic_file', 'has-error') }}">
                                            <label for="pic" class="col-sm-2 control-label">Profile picture</label>
                                            <div class="col-sm-10">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                        @if($user->pic)

                                                            @if((substr($user->pic, 0,5)) == 'https')
                                                                <img src="{{ $user->pic }}" alt="img"
                                                                        class="img-responsive"/>
                                                            @else
                                                            <img src="{!! secure_url('/').'/uploads/perfiles/'.$user->pic !!}" alt="img"
                                                                    class="img-responsive"/>
                                                            @endif
                                                        @elseif($user->gender === "male")
                                                            <img src="{{ secure_asset('assets/images/authors/avatar3.png') }}" alt="..."
                                                                    class="img-responsive"/>
                                                        @elseif($user->gender === "female")
                                                            <img src="{{ secure_asset('assets/images/authors/avatar5.png') }}" alt="..."
                                                                    class="img-responsive"/>
                                                        @else
                                                            <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."
                                                                    class="img-responsive"/>
                                                        @endif
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                    <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Seleccionar Imagen</span>
                                                <span class="fileinput-exists">Cambiae</span>
                                                <input id="pic" name="pic_file" type="file"
                                                        class="form-control"/>
                                            </span>
                                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="color: black !important;">Eliminar</a>
                                                    </div>
                                                </div>
                                                {!! $errors->first('pic_file', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="marketing_cliente" class="col-sm-2 control-label"> Marketing</label>
                                            <div class="col-sm-10">
                                                <input id="marketing_cliente" name="marketing_cliente" type="checkbox"
                                                       class="pos-rel p-l-30 custom-checkbox "
                                                       value="1" @if(old('marketing_cliente', $clien->marketing_cliente) ) checked="checked" @endif >
                                                <span>¿Acepta recibir campañas de marketing en su email?</span></div>

                                        </div>

                                        

                                    </div>
                                    

                                    <div class="tab-pane" id="tab4" disabled="disabled">
                                        <div class="alert alert-danger alert-dismissable margin5">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <strong>Importante:</strong> Dede ser cuidadoso al seleccionar el grupo de usuarios, ya que el resultado se ve reflejado en los precios de la tienda.
                                        </div>
                                        <div class="form-group required">
                                            <label for="group" class="col-sm-2 control-label">Grupo de Usuarios *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control " title="Seleccionar grupo de usuarios..." name="groups" id="groups" required>
                                                    <option value="">Seleccionar Grupo de Usuarios</option>
                                                    @foreach($roles as $role)
                                                        <option value="{!! $role->id !!}" {{ (array_key_exists($role->id, $userRoles) ? ' selected="selected"' : '') }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('group', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('group', ':message') }}</span>
                                        </div>
                                        @if(!empty($clien->first_name))
                                        <div class="form-group {{ $errors->first('embajador', 'has-error') }}">
                                            <label for="embajador" class="col-sm-2 control-label">Embajador</label>
                                            <div class="col-sm-10">
                                                <input id="embajador" name="embajador" placeholder="E-mail" type="text"
                                                       class="form-control required embajador" value="{!! old('first_name', $clien->first_name.' '.$clien->last_name) !!}" readonly/>
                                                {!! $errors->first('embajador', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <ul class="pager wizard">
                                        <li class="previous"><a href="#">@lang('clientes/title.previous')</a></li>
                                        <li class="next"><a href="#">@lang('clientes/title.next')</a></li>
                                        <li class="next finish" style="display:none;"><a href="javascript:;">Guardar</a></li>
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

