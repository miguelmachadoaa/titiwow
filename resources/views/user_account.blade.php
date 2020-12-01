@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Mi Cuenta
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

 <link rel="canonical" href="{{secure_url('my-account')}}" />

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/iCheck/css/minimal/blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
{{--    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>--}}
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/user_account.css') }}">

@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ secure_url('clientes') }}">Mi Perfil </a>
                </li>

                
            </ol>
        </div>
    </div>
@stop

{{-- Page content --}}
@section('content')
    <hr class="content-header-sep">
    <div class="container">
        <div class="welcome">
            <h3>Mi Cuenta</h3>
        </div>
        <hr>
        <div class="row margin_right_left">
            <div class="row margin_right_left">
                <div class="col-md-12">
                    <!--main content-->
                    <div class="position-center">
                        <!-- Notifications -->
                        <div id="notific">
                        @include('notifications')
                        </div>

                        @if(isset($user->id))
                        {!! Form::model($user, ['url' => secure_url('my-account'), 'method' => 'put', 'class' => 'form-horizontal','enctype'=>"multipart/form-data"]) !!}

                        {{ csrf_field() }}
                           
                            <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Nombre:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                    <span class="input-group-addon">
                        <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                    </span>
                                        <input type="text" placeholder=" " name="first_name" id="uf-name"
                                               class="form-control" value="{!! old('first_name',$user->first_name) !!}">
                                    </div>
                                    <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
                                </div>

                            </div>

                            <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Apellido:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                        <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                            </span>
                                        <input type="text" placeholder=" " name="last_name" id="ul-name"
                                               class="form-control"
                                               value="{!! old('last_name',$user->last_name) !!}"></div>
                                    <span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Email:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                                                <span class="input-group-addon">
                        <i class="livicon" data-name="mail" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                                                </span>
                                        <input type="text" placeholder=" " id="email" name="email" class="form-control"
                                               value="{!! old('email',$user->email) !!}"></div>
                                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                                </div>

                            </div>

                            <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                <p class="text-warning col-md-offset-2"><strong>Si no deseas cambiar la Contraseña, por favor dejar en blanco.</strong></p>
                                <label class="col-lg-2 control-label">
                                    Contraseña:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                        <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                            </span>
                                        <input type="password" name="password" placeholder=" " id="pwd" class="form-control"></div>
                                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Confirmar Contraseña:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                        <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                            </span>
                                        <input type="password" name="password_confirm" placeholder=" " id="cpwd" class="form-control"></div>
                                    <span class="help-block">{{ $errors->first('password_confirm', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Genero: </label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="male" @if($user->gender === "male") checked="checked" @endif />
                                            Masculino
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="female" @if($user->gender === "female") checked="checked" @endif />
                                            Femenino
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="other" @if($user->gender === "other") checked="checked" @endif />
                                            Otro
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dob', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Fecha de Nacimiento:
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                        <i class="livicon" data-name="calendar" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                            </span>
{{--                                        @if($user->dob === "0000-00-00")--}}
{{--                                            {!!  Form::text('dob', '', array('id' => 'datepicker','class' => 'form-control'))  !!}--}}
                                            @if($user->dob === '')
                                                {!!  Form::text('dob', null, array('id' => 'datepicker','class' => 'form-control'))  !!}
                                        @else
                                                 {!!  Form::text('dob', old('dob',$user->dob), array('id' => 'datepicker','class' => 'form-control', 'data-date-format'=> 'YYYY-MM-DD'))  !!}
                                        @endif
                                    </div>
                                    <span class="help-block">{{ $errors->first('dob', ':message') }}</span>
                                </div>
                            </div>

                        <div class="col-sm-12">

                            <label class="col-lg-2 control-label">
                                    
                                </label>


                          <div class="form-group {{ $errors->first('marketing_email', 'has-error') }} checkbox">
                             <label style="padding: 0;">
                                
                                  <input type="checkbox" name="marketing_email" id="marketing_email" value="1" @if($cliente->marketing_sms==1) checked @endif>  Me gustaria recibir promociones de productos y servicios por Email.
                              </label>
                              {!! $errors->first('marketing_email', '<span class="help-block">:message</span>') !!}
                          </div>

                           <p style="color: red;" class="error_marketing_email"></p>
                          

                        </div>


                         <div class="col-sm-12">

                            <label class="col-lg-2 control-label">
                                    
                            </label>


                          <div class="form-group {{ $errors->first('marketing_sms', 'has-error') }} checkbox">
                             <label style="padding: 0;">
                                
                                  <input type="checkbox" name="marketing_sms" id="marketing_sms" value="1"  @if($cliente->marketing_sms==1) checked @endif>  Me gustaria recibir promociones de productos y servicios por Sms.
                              </label>
                              {!! $errors->first('marketing_sms', '<span class="help-block">:message</span>') !!}
                          </div>

                           <p style="color: red;" class="error_marketing_sms"></p>
                          

                        </div>









                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <a class="btn btn-danger" type="button" href="{{ secure_url('clientes') }}">Regresar</a>
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                </div>
                            </div>

                        </form>{{--{!!  Form::close()  !!}--}}

                        @else

                            <div class="alert alert-danger">No se Encontro usuario. Por favor intente Nuevamente.</div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



<div class="container">
    <div class="form-group">
        <div class="col-lg-offset-10 col-lg-10" style="margin-bottom:20px;">
            @if(empty($cliente->cod_alpinista))
                <a style="color: red !important;" href="{{ secure_url('#') }}" class="btn btn-link delete" type="button">
                 Eliminar Cuenta 
                </a>
            @endif
        </div>
    </div>

    
    
</div>





<!-- Modal Direccion -->
 <div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Cuenta</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="aprobarOrdenForm" name="aprobarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                           
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10">
                                    <h3>Esta seguro que desea eliminar la cuenta?</h3>
                                </div>

                            </div>

                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendEliminar" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>








@stop




{{-- page level scripts --}}
@section('footer_scripts')

    <script type="text/javascript" src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/user_account.js') }}"></script>

    <script type="text/javascript">


        $('.delete').on('click', function(e){

            e.preventDefault(); 

            $("#deleteModal").modal('show');


        });



         $('.sendEliminar').click(function () {

            base=$('#base').val();

            $.ajax({
                type: "GET",
                url: base+"/admin/clientes/delcliente",
                    
                complete: function(datos){ 

                   // alert(datos);    

                    $("#deleteModal").modal('hide');
                    

                }
            });
        

        });


         </script>

@stop
