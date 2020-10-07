@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Cliente 
@parent
@stop


{{-- page level styles --}}
@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/timeline.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/timeline2.css') }}" rel="stylesheet" />
    
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Ver Historial de Cliente  
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Clientes</li>
        <li class="active">Ver</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">




     <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Datos del Cliente
                                </h3>
                                
                            </div>
                            <div class="panel-body">


                                <div class="col-md-8">

                                        
                                            

                    <div class="table-responsive-lg table-responsive-sm table-responsive-md table-responsive">
                        <table class="table table-bordered table-striped" id="users">

                            <tr>
                                <td><b> Rol</b></td>
                                <td>
                                    <b></b>{{ $usuario->name_rol }}
                                </td>
                            </tr>

                            <tr>
                                <td><b> Nombre</b></td>
                                <td>
                                    <p class="user_name_max">{{ $usuario->first_name }}</p>
                                </td>

                            </tr>
                            <tr>
                                <td><b>Apellido </b></td>
                                <td>
                                    <p class="user_name_max">{{ $usuario->last_name }}</p>
                                </td>

                            </tr>
                            <tr>
                                <td><b> Email</b></td>
                                <td>
                                    {{ $usuario->email }}
                                </td>
                            </tr>

                              <tr>
                                <td><b> Documento</b></td>
                                <td>
                                    {{ $cliente->nombre_tipo_documento.': '.$cliente->doc_cliente }}
                                </td>
                            </tr>

                             <tr>
                                <td><b> Teléfono</b></td>
                                <td>
                                    {{ $cliente->telefono_cliente }}
                                </td>
                            </tr>


                            <tr>
                                <td><b> Código Cliente</b></td>
                                <td>
                                    {{ $cliente->codigo_cliente }}
                                </td>
                            </tr>

                            <tr>
                                <td><b> Registro Ibm</b></td>
                                <td>
                                   @if($cliente->estatus_ibm==0)

                                   {{'No Registrado en Ibm'}}

                                   @else

                                   {{'Registrado en Ibm'}}

                                   @endif
                                </td>
                            </tr>

                            @if( isset($cliente->embajador) )

                            <tr>
                                <td><b> Embajador</b></td>
                                <td>
                                    {{ $cliente->embajador->first_name.' '.$cliente->embajador->last_name }}
                                </td>
                            </tr>

                            <tr>
                                <td><b> Embajador Email</b></td>
                                <td>
                                    {{ $cliente->embajador->email }}
                                </td>
                            </tr>

                            @endif

                            @if(isset($un_saldo->id))

                                @if(isset($disponible[$cliente->id_user_client]))

                                     <tr>
                                        <td><b> Saldo Disponible</b></td>
                                        <td>
                                            {{ $disponible[$cliente->id_user_client] }}
                                        </td>
                                    </tr>

                                @else

                                    <tr>
                                        <td><b> Saldo Disponible</b></td>
                                        <td>
                                            {{ '0' }}
                                        </td>
                                    </tr>

                                @endif

                                <tr>
                                    <td><b> Fecha de Vencimiento</b></td>
                                    <td>
                                        {{ $un_saldo->fecha_vencimiento }}
                                    </td>
                                </tr>


                            @endif


                             @if($cliente->origen='1')

                                     <tr>
                                        <td><b> Origen</b></td>
                                        <td>
                                           Tomapedidos
                                        </td>
                                    </tr>


                                    @if($cliente->tomapedidos_tratamiento=='1')

                                     <tr>
                                        <td><b> Acepto</b></td>
                                        <td>
                                           Terminos y Condiciones Tomapedidos
                                        </td>
                                    </tr>


                                    @else

                                         <tr>
                                            <td><b> No Acepto</b></td>
                                            <td>
                                               Terminos y Condiciones Tomapedidos
                                            </td>
                                        </tr>
                                    @endif

                                    @if($cliente->tomapedidos_marketing=='1')

                                        <tr>
                                        <td><b> Acepto</b></td>
                                        <td>
                                           Envio de Promociones y Ofertas 
                                        </td>
                                    </tr>


                                    @else

                                         <tr>
                                            <td><b> No Acepto</b></td>
                                            <td>
                                               Envio de Promociones y Ofertas 
                                            </td>
                                        </tr>
                                    @endif





                                @else







                                   <tr>
                                        <td><b> Origen</b></td>
                                        <td>
                                           Web
                                        </td>
                                    </tr>




                                      @if($cliente->habeas_cliente=='1')

                                     <tr>
                                        <td><b> Acepto</b></td>
                                        <td>
                                           Terminos y Condiciones 
                                        </td>
                                    </tr>


                                    @else

                                         <tr>
                                            <td><b> No Acepto</b></td>
                                            <td>
                                               Terminos y Condiciones 
                                            </td>
                                        </tr>
                                    @endif



                                    @if($cliente->marketing_cliente=='1')

                                        <tr>
                                        <td><b> Acepto</b></td>
                                        <td>
                                           Envio de Promociones y Ofertas 
                                        </td>
                                    </tr>


                                    @else

                                         <tr>
                                            <td><b> No Acepto</b></td>
                                            <td>
                                               Envio de Promociones y Ofertas 
                                            </td>
                                        </tr>
                                    @endif












                                @endif

                           
                        </table>

                        <br>

                    @if($usuario->role_id=='10')
                
                    <div class="col-sm-12">
                        <button data-id="{{$usuario->id}}"  class="btn btn-primary changerol">Asignar usuario a Invitados Alpina </button>
                    </div>

                    @endif



                                                </div>
                                            </div>

                               
                            </div>
                        </div>
                    </div>
                </div>






@if(count($saldo))

    <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Movimientos del Saldo
                                </h3>
                                
                            </div>
                            <div class="panel-body">

                                    <table class="table table-responsive" id="tbAmigos">
                        <thead>
                            <tr>
                                <th>Saldo</th>
                                <th>Operación</th>
                                
                                <th>Fecha de vencimiento</th>
                                <th>Nota</th>
                                <th>Creado</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($saldo as $s)

                                <tr>
                                    <td>
                                        {{ $s->saldo }}
                                    </td>
                                    <td>
                                        @if($s->operacion=='1')

                                            {{ 'Ingreso' }}

                                        @else

                                            {{ 'Compra' }}

                                        @endif
                                        
                                    </td>

                                    <td>
                                        {{ $s->fecha_vencimiento }}
                                    </td>

                                    <td>
                                        {{ $s->nota }}
                                    </td>
                                    
                                    <td>
                                        {{ $s->created_at }}
                                    </td>
                                    
                                </tr>
                            
                        @endforeach
                        </tbody>
                    </table>
                               
                            </div>
                        </div>
                    </div>
                </div>

              
@endif
    

@if($usuario->role_id==10)

<div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Amigos
                                </h3>
                                
                            </div>
                            <div class="panel-body">

                                

                                    <table class="table table-responsive" id="tbAmigos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Puntos</th>
                                <th>Creado</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($referidos as $referido)

                                <tr>
                                    <td>
                                        {{ $referido->first_name }}
                                    </td>
                                    <td>
                                        {{ $referido->last_name }}
                                    </td>
                                    <td>
                                        {{ $referido->email }}
                                    </td>
                                    <td>
                                        {{ number_format($referido->puntos,0,",",".")  }}
                                    </td>
                                    <td>
                                        {{ $referido->created_at }}
                                    </td>

                                    
                                </tr>
                            
                        @endforeach
                        </tbody>
                    </table>

                                
                               
                            </div>
                        </div>
                    </div>
                </div>

              
@endif
    


     <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Historia de Cambios
                                </h3>
                                
                            </div>
                            <div class="panel-body">
                                <!--timeline-->
                                <div class="row">
                                    <ul class="timeline">


                                         @foreach($history as $indexKey => $row)

                                            @if($indexKey%2==0)

                                            <li>
                                            <div class="timeline-badge">
                                                <i class="livicon" data-name="users" data-c="#fff" data-hc="#fff" data-size="18" data-loop="true"></i>
                                            </div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">{{ ucwords($row->estatus_cliente) }}</h4>
                                                    <p>
                                                        <small class="text-muted">
                                                            <i class="livicon" data-name="bell" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
                                                            {!! $row->created_at->diffForHumans().' por  '.$row->first_name.' '.$row->last_name !!}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p>
                                                        {{ $row->notas }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>

                                            @else

                                             <li class="timeline-inverted">
                                            <div class="timeline-badge">
                                                <i class="livicon" data-name="users" data-c="#fff" data-hc="#fff" data-size="18" data-loop="true"></i>
                                            </div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">{{ ucwords($row->estatus_cliente) }}</h4>
                                                    <p>
                                                        <small class="text-muted">
                                                            <i class="livicon" data-name="bell" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
                                                            {!! $row->created_at->diffForHumans().' por  '.$row->first_name.' '.$row->last_name !!}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p>
                                                        {{ $row->notas }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>

                                            @endif
                                         

                                         @endforeach


                                    </ul>
                                </div>
                                <!--timeline ends-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--timeline2-->

        <br>

    



                <div class="row">
                
            <p style="text-align: center;"> 
                    <a class="btn btn-default" href="{{ secure_url('admin/clientes') }}">Volver</a>

            </p>

        </div>

        <input type="hidden" name="base" id="base" value="{{ secure_url('/')}}">


</section>


<!-- Main content -->

@stop


{{-- page level scripts --}}
@section('footer_scripts')



    <!-- Modal Direccion -->
    <div class="modal fade" id="rolModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar </h4>
                        
                </div>
                
                <div class="modal-body " style="min-height: 10em;">
                    <input type="hidden" name="usuario_id" id="usuario_id" value="">
                     
                    <h3>Esta seguro de que desea asignar el usuario a <b>Invitados Alpina</b>, tenga en cuenta que si este usuario tiene amigos, ellos tambien se asignaran a Invitados Alpina.</h3>

                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button"   class="btn  btn-primary saveInvitacion" >Aceptar</button>
                    
                </div>
            </div>
        </div>
    </div>



<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>



    <script>
        $(document).ready(function(){

            $('.changerol').on('click', function(){

                $('#usuario_id').val($(this).data('id'));
                $('#rolModal').modal('show');
            });


            $('.saveInvitacion').on('click', function(){

                cliente=$('#usuario_id').val();
                base=$('#base').val();

                $.ajax({
                    type: "POST",
                    data:{  cliente },
                    url: base+"/admin/clientes/updaterol",
                        
                    complete: function(datos){     

                        if (datos.responseText=='true') {

                            location.reload();

                        }else{

                            alert('Hubo un error al actualizar intente nuevamente');
                        }

                    }
                });

            });



        });
    </script>

@stop
