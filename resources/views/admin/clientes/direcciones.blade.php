@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Direcciones del Cliente {{ $user->id }}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ secure_asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet"/>

    <link href="{{ secure_asset('assets/css/pages/user_profile.css') }}" rel="stylesheet"/>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>Direcciones del Cliente {{ $user->id }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    Escritorio
                </a>
            </li>
            <li>
                <a href="#">Clientes</a>
            </li>
            <li class="active">Dirección</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">

        <div class="row">

            @if (count($direcciones)>0)

                @foreach($direcciones as $direccion)

                    <div class="form-group ">

                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="panel panel-default" style="padding: 1em;   @if ($direccion->default_address=='1'){{'border: red 5px solid; '}}
                            @endif   ">
                                
                                <div class="panel-body">
                                    <div class="box-body">
                                        <dl class="dl-horizontal" style="    padding: 1EM 0EM;">

                                            <dt>Departamento</dt>
                                            <dd>{{ $direccion->state_name }}</dd>

                                            <dt>Ciudad</dt>
                                            <dd>{{$direccion->city_name }}</dd>

                                           
                                            <dt>Direccion</dt>
                                            <dd>
                                               {{ $direccion->nombre_estructura.' '.$direccion->principal_address.'  #'.$direccion->secundaria_address .'-'.$direccion->edificio_address.' '.$direccion->detalle_address  }}
                                            </dd>

                                            <dt>Barrio</dt>
                                            <dd>
                                               {{ $direccion->barrio_address }}
                                            </dd>

                                            <dt>Notas</dt>
                                            <dd>{{ $direccion->notas }}</dd>
                                            
                                        </dl>
                                    </div>
                                    <!-- /.box-body -->
                                </div>

                                <a class="btn btn-info btn-xs" href="{{ secure_url('admin/clientes/editdir/'.$direccion->id) }}">Editar</a>


                                @if ($direccion->default_address=='0')
                                    <a class="btn btn-success btn-xs" href="{{ secure_url('admin/clientes/setdir/'.$direccion->id) }}">Definir por Defecto</a>
                                @endif

                            </div>

                             


                        </div>
                     

                        <!-- Se construyen las opciones de envios -->

                       


                @endforeach
                        

            @else
                <div class="alert alert-danger">
                        <p>El Cliente aun no posee direcciones Registradas</p>
                    </div>
            @endif     

        </div>

        <div class="row">

            <div class="col-sm-12">
                
                <a class="btn btn-primary" href="{{ secure_url('admin/clientes/adddir/'.$user->id) }}">Agregar Dirección</a>

                <a class="btn btn-default" href="{{ secure_url('admin/clientes') }}">Volver</a>

            </div>

        </div>
        
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- Bootstrap WYSIHTML5 -->
    <script  src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
        <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

        

    <script type="text/javascript">
        $(document).ready(function () {
            $('#change-password').click(function (e) {
                e.preventDefault();
                var check = false;
                if ($('#password').val() ===""){
                    alert('Please Enter password');
                }
                else if  ($('#password').val() !== $('#password-confirm').val()) {
                    alert("confirm password should match with password");
                }
                else if  ($('#password').val() === $('#password-confirm').val()) {
                    check = true;
                }

                if(check == true){
                var sendData =  '_token=' + $("input[name='_token']").val() +'&password=' + $('#password').val() +'&id=' + {{ $user->id }};
                    var path = "passwordreset";
                    $.ajax({
                        url: path,
                        type: "post",
                        data: sendData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                        },
                        success: function (data) {
                            $('#password, #password-confirm').val('');
                            alert('password reset successful');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert('error in password reset');
                        }
                    });
                }
            });
        });
    </script>

@stop
