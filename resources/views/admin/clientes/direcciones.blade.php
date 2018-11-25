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
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#">Users</a>
            </li>
            <li class="active">User Profile</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">

        <div class="row">

            @if (isset($direcciones->id))


                
                    <div class="form-group ">

                    <input type="hidden" name="id_direccion"  id="id_direccion" value="{{ $direcciones->id }}" >  


                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-horizontal">

                                    <dt>Departamento</dt>
                                    <dd>{{ $direcciones->state_name }}</dd>

                                    <dt>Ciudad</dt>
                                    <dd>{{$direcciones->city_name }}</dd>

                                   
                                    <dt>Direccion</dt>
                                    <dd>
                                       {{ $direcciones->nombre_estructura.' '.$direcciones->principal_address.' - '.$direcciones->secundaria_address }}
                                    </dd>

                                    <dt>Apto, Puerta Interior</dt>
                                    <dd>
                                       {{ $direcciones->edificio_address.' '.$direcciones->detalle_address }}
                                    </dd>

                                    <dt>Barrio</dt>
                                    <dd>
                                       {{ $direcciones->barrio_address }}
                                    </dd>

                                    <dt>Notas</dt>
                                    <dd>{{ $direcciones->notas }}</dd>
                                    
                                </dl>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>
                </div>
                     

                        <!-- Se construyen las opciones de envios -->

                       


                       

            @else
                <div class="alert alert-danger">
                        <p>El Cliente aun no posee direcciones Registradas</p>
                    </div>
            @endif     

            

            
             
            
            

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <div class="row">
                
            <p style="text-align: center;"> 
                    <a class="btn btn-default" href="{{ secure_url('admin/clientes') }}">Volver</a>

            </p>

        </div>
        
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- Bootstrap WYSIHTML5 -->
    <script  src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>

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
