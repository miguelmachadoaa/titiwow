@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Editar Usuario
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
        <h1>Editar Usuario</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Usuarios</li>
            <li class="active">Agregar Usuario</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"> <i class="livicon" data-name="users" data-size="16" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Editando Usuario : <p class="user_name_max">{!! $user->first_name!!} {!! $user->last_name!!}</p>
                        </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">

                            <div class="col-md-12">

                                {!! Form::model($user, ['url' => secure_url('admin/users/'. $user->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                                    {{ csrf_field() }}

                                    <div id="rootwizard">
                                        <ul>
                                            <li><a href="#tab1" data-toggle="tab">Perfil de Usuario</a></li>
                                            <li><a href="#tab2" data-toggle="tab">Datos Biograficos</a></li>
                                            <li><a href="#tab3" data-toggle="tab">Direccion</a></li>
                                            <li><a href="#tab4" data-toggle="tab">Rol de Usuario</a></li>
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
                                                    </div>
                                                    {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                                    <label for="last_name" class="col-sm-2 control-label">Apellido*</label>
                                                    <div class="col-sm-10">
                                                        <input id="last_name" name="last_name" type="text" placeholder="Apellido"
                                                               class="form-control required"
                                                               value="{!! old('last_name', $user->last_name) !!}"/>
                                                    </div>
                                                    {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                                    <label for="email" class="col-sm-2 control-label">Email *</label>
                                                    <div class="col-sm-10">
                                                        <input id="email" name="email" placeholder="E-Mail" type="text"
                                                               class="form-control required email"
                                                               value="{!! old('email', $user->email) !!}"/>

                                                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                                    <p class="text-warning">Si no desea cambiar la Contraseña, no rellene estos campos</p>
                                                    <label for="password" class="col-sm-2 control-label">Password </label>
                                                    <div class="col-sm-10">
                                                        <input id="password" name="password" type="password" placeholder="Password"
                                                               class="form-control" value="{!! old('password') !!}"/>
                                                    </div>
                                                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                                    <label for="password_confirm" class="col-sm-2 control-label">Confirmar Password </label>
                                                    <div class="col-sm-10">
                                                        <input id="password_confirm" name="password_confirm" type="password"
                                                               placeholder="Confirmar Password " class="form-control"
                                                               value="{!! old('password_confirm') !!}"/>
                                                        {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="tab-pane" id="tab2" disabled="disabled">
                                                <h2 class="hidden">&nbsp;</h2>
                                                <div class="form-group {{ $errors->first('dob', 'has-error') }}">
                                                    <label for="dob" class="col-sm-2 control-label">Fecha de Nacimiento</label>
                                                    <div class="col-sm-10">
                                                        <input id="dob" name="dob" type="text" class="form-control"
                                                               data-date-format="YYYY-MM-DD" value="{!! old('dob', $user->dob) !!}"
                                                               placeholder="yyyy-mm-dd"/>
                                                    </div>
                                                    {!! $errors->first('dob', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('pic_file', 'has-error') }}">
                                                    <label for="pic" class="col-sm-2 control-label">Imagen de Perfil</label>
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
                                                        <span class="fileinput-new">Select image</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input id="pic" name="pic_file" type="file"
                                                               class="form-control"/>
                                                    </span>
                                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="color: black !important;">Remove</a>
                                                            </div>
                                                        </div>
                                                        {!! $errors->first('pic_file', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group  {{ $errors->first('pic', 'has-error') }}">


                                                    <label for="bio" class="col-sm-2 control-label">Biogracias <small>(Introduccion)</small></label>
                                                    <div class="col-sm-10">
                                            <textarea name="bio" id="bio" class="form-control resize_vertical"
                                                      rows="4">{!! old('bio', $user->bio) !!}</textarea>
                                                    </div>
                                                    {!! $errors->first('bio', '<span class="help-block">:message</span>') !!}
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="tab3" disabled="disabled">
                                                <div class="form-group {{ $errors->first('gender', 'has-error') }}">
                                                    <label for="email" class="col-sm-2 control-label">Genero </label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" title="Seleccione Genero" name="gender">
                                                            <option value="">Seleccione</option>
                                                            <option value="male" @if($user->gender === 'male') selected="selected" @endif >Masculino</option>
                                                            <option value="female" @if($user->gender === 'female') selected="selected" @endif >Famanino</option>
                                                            <option value="other" @if($user->gender === 'other') selected="selected" @endif >Otro</option>

                                                        </select>
                                                    </div>
                                                    {!! $errors->first('gender', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group required {{ $errors->first('country', 'has-error') }}">
                                                    <label for="country" class="col-sm-2 control-label">Pais </label>
                                                    <div class="col-sm-10">
                                                        {!! Form::select('country', $countries,old('country',$user->country),array('class' => 'country_field form-control')) !!}

                                                    </div>
                                                    {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('state', 'has-error') }}">
                                                    <label for="state"
                                                           class="col-sm-2 control-label">Departamento </label>
                                                    <div class="col-sm-10">
                                                        <input id="state" name="state" type="text"
                                                               class="form-control"
                                                               value="{!! old('state', $user->state) !!}"/>
                                                    </div>
                                                    {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('city', 'has-error') }}">
                                                    <label for="city" class="col-sm-2 control-label">Ciudad </label>
                                                    <div class="col-sm-10">
                                                        <input id="city" name="city" type="text" class="form-control"
                                                               value="{!! old('city', $user->city) !!}"/>
                                                    </div>
                                                    {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('address', 'has-error') }}">
                                                    <label for="address" class="col-sm-2 control-label">Direccion </label>
                                                    <div class="col-sm-10">
                                                        <input id="address" name="address" type="text" class="form-control"
                                                               value="{!! old('address', $user->address) !!}"/>
                                                    </div>
                                                    {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('postal', 'has-error') }}">
                                                    <label for="postal" class="col-sm-2 control-label">Codigo Postal</label>
                                                    <div class="col-sm-10">
                                                        <input id="postal" name="postal" type="text" class="form-control"
                                                               value="{!! old('postal', $user->postal) !!}"/>
                                                    </div>
                                                    {!! $errors->first('postal', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab4" disabled="disabled">
                                                <p class="text-danger"><strong>Tenga cuidado con la selección de grupos, si le da acceso al administrador ... ellos pueden acceder a la sección de administración</strong></p>
                                                <div class="form-group {{ $errors->first('group', 'has-error') }}">
                                                    <label for="group" class="col-sm-2 control-label">Rol *</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control " title="Seleccione Rol..." name="groups[]" id="groups" required>
                                                            <option value="">Seleccione</option>
                                                            @foreach($roles as $role)
                                                                <option value="{!! $role->id !!}" {{ (array_key_exists($role->id, $userRoles) ? ' selected="selected"' : '') }}>{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div
                                                            {!! $errors->first('group', '<span class="help-block">:message</span>') !!}>
                                                </div>


                                                <div class="form-group {{ $errors->first('almacen', 'has-error') }}">
                                                    <label for="almacen" class="col-sm-2 control-label">Almacen <small>Solo es Requerido para el rol almacen</small></label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control " title="Seleccione Almacen." name="almacen" id="almacen" required>
                                                            <option value="">Seleccione</option>
                                                            <option value="0" @if($user->almacen==0)
                                                                    {{'Selected'}}
                                                                @endif >No Aplica</option>
                                                            @foreach($almacenes as $almacen)
                                                                <option value="{!! $almacen->id !!}" 
                                                                @if($almacen->id==$user->almacen)
                                                                    {{'Selected'}}
                                                                @endif

                                                                >{{ $almacen->nombre_almacen }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div
                                                            {!! $errors->first('almacen', '<span class="help-block">:message</span>') !!}>
                                                </div>




                                                <div class="form-group">
                                                    <label for="activate" class="col-sm-2 control-label"> Activar Usuario</label>
                                                    <div class="col-sm-10">
                                                        <input id="activate" name="activate" type="checkbox" class="pos-rel p-l-30 custom-checkbox" value="1" @if($status) checked="checked" @endif  >
                                                        <span>Para activar cuenta active la casilla</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="pager wizard">
                                                <li class="previous"><a href="#">Anterior</a></li>
                                                <li class="next"><a href="#">Siguiente</a></li>
                                                <li class="next finish" style="display:none;"><a href="javascript:;">Finalizar</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!--main content end-->
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/js/pages/edituser.js') }}"></script>
    <script>
        function formatState (state) {
            if (!state.id) { return state.text; }
            var $state = $(
                '<span><img src="{{secure_asset('assets/img/countries_flags')}}/'+ state.element.value.toLowerCase() + '.png" class="img-flag" width="20px" height="20px" /> ' + state.text + '</span>'
            );
            return $state;

}
$(".country_field").select2({
    templateResult: formatState,
    templateSelection: formatState,
    placeholder: "select a country",
    theme:"bootstrap"
});


</script>
@stop
