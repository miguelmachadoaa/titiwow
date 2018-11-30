<!DOCTYPE html>
<html>

<head>
    <title>Olvidé Mi Contraseña | Alpina Go!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <link href="{{ secure_asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <!-- end of global level css -->
    <!-- page level css -->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/pages/login.css') }}" />
    <link href="{{ secure_asset('assets/vendors/iCheck/css/square/blue.css') }}" rel="stylesheet"/>
    <!-- end of page level css -->

</head>

<body>

<div class="container">
    <div class="row vertical-offset-100">
        <!-- Notifications -->
        <div id="notific">
            @include('notifications')
        </div>

        <div class="col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div id="container_demo">
                <a class="hiddenanchor" id="toregister"></a>
                <a class="hiddenanchor" id="tologin"></a>
                <a class="hiddenanchor" id="toforgot"></a>
                <div id="wrapper">
                    <div id="login" class="animate form">
                        <form method="post" action="{{ secure_url('admin/forgot-password',compact(['userId','passwordResetCode'])) }}" class="form-horizontal">
                            <h3 >
                                <img src="{{ secure_asset('assets/img/login.png') }}" alt="Alpina Go!">
                                <br>Olvidé Mi Contraseña</h3>
                                <!-- CSRF Token -->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="back" id="back" value="0">
                                
                            <!--div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <label style="margin-bottom:0px;" for="email" class="uname control-label"> <i class="livicon" data-name="mail" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                    E-mail
                                </label>
                                <input id="email" name="email" type="email" placeholder="E-mail"
                                        value="{!! old('email') !!}"/>
                                <div class="col-sm-12">
                                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                <label style="margin-bottom:0px;" for="password" class="youpasswd"> <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                    Contraseña
                                </label>
                                <input id="password" name="password" type="password" placeholder="Contraseña" />
                                <div class="col-sm-12">
                                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div-->
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <!-- New Password -->
                            <div class="form-group{{ $errors->first('password', ' has-error') }} col-sm-12">
                                <label for="password">@lang('auth/form.newpassword')</label>
                                <input type="password" name="password" id="password" value="{{ old('password') }}"
                                    class="form-control"/>
                                {{ $errors->first('password', '<span class="help-block">:message</span>') }}
                            </div>

                            <!-- Password Confirm -->
                            <div class="form-group{{ $errors->first('password_confirm', ' has-error') }} col-sm-12">
                                <label class="control-label" for="password_confirm">@lang('auth/form.confirmpassword')</label>
                                <input type="password" name="password_confirm" id="password_confirm"
                                    value="{{ old('password_confirm') }}" class="form-control"/>
                                {{ $errors->first('password_confirm', '<span class="help-block">:message</span>') }}
                            </div>

                            <p class="login button">
                                <input type="submit" value="Cambiar Contraseña" class="btn btn-success" />
                            </p>
                            <p class="change_link">
                                <a href="{{ secure_url('admin') }}">
                                    <button type="button" class="btn btn-responsive botton-alignment btn-danger btn-sm">Cancelar</button>
                                </a>
                                <!--a href="#toregister">
                                    <button type="button" id="signup" class="btn btn-responsive botton-alignment btn-success btn-sm" style="float:right;">Sign Up</button>
                                </a-->
                            </p>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <!-- global js -->
    <script src="{{ secure_asset('assets/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <!-- Bootstrap -->
    <script src="{{ secure_asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <!--livicons-->
    <script src="{{ secure_asset('assets/js/raphael-min.js') }}"></script>
    <script src="{{ secure_asset('assets/js/livicons-1.4.min.js') }}"></script>
    <script src="{{ secure_asset('assets/js/pages/login.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>

    <!-- end of global js -->
</body>
</html>