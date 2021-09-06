@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Abono
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop



{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Bonos</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Bonos </a></li>
        <li class="active">Listado</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Bonos
                    </h4>
                    <div class="pull-right">
                    <a href="{{ secure_url('admin/abonos/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear Bono</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table">
                            <tbody>

                                <tr>
                                    <td>Id</td>
                                    <td>{{$abono->id}}</td>
                                </tr>

                                <tr>
                                    <td>Codigo</td>
                                    <td>{{$abono->codigo_abono}}</td>
                                </tr>

                                <tr>
                                    <td>Valor</td>
                                    <td>{{$abono->valor_abono}}</td>
                                </tr>

                                <tr>
                                    <td>Fecha Limite</td>
                                    <td>{{$abono->fecha_final}}</td>
                                </tr>

                                <tr>
                                    <td>Origen</td>
                                    <td>{{$abono->origen}}</td>
                                </tr>

                                <tr>
                                    <td>Nombre Almacen</td>
                                    <td>{{$abono->nombre_almacen}}</td>
                                </tr>

                                <tr>
                                    <td>Estado</td>
                                    <td>{{$abono->estado_registro}}</td>
                                </tr>
                                
                                <tr>
                                    <td>Creado por</td>
                                    <td>{{$abono->first_name.' '.$abono->last_name}}</td>
                                </tr>
                                <tr>
                                    <td>Fecha Creacioón</td>
                                    <td>{{$abono->created_at}}</td>
                                </tr>

                            </tbody>
                           
                        </table>
                        </div>
                 
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>



<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Bono Uso
                    </h4>
                    
                </div>
                <br />
                <div class="panel-body">

                <table class="table">
                    <tr>
                        <td>Id</td>
                        <td>Monto</td>
                        <td>Origen</td>
                        <td>Orden</td>
                        <td>Actualizado </td>
                        <td>Fecha</td>
                    </tr>

                    @foreach($history as $h)

                        <tr>
                            <td>{{$h->id}}</td>
                            <td>{{$h->valor_abono}}</td>
                            <td>{{$h->origen}}</td>
                            <td>
                                @if(isset(json_decode($h->json)->referencia))
                                    {{json_decode($h->json)->referencia}}
                                @endif
                            </td>
                            <td>{{$h->first_name.' '.$h->last_name}}</td>
                            <td>{{$h->created_at}}</td>
                        </tr>

                    @endforeach
                </table>
               
                 
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>







@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<div class="modal fade" id="users_exists" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                @lang('groups/message.users_exists')
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>


<script>


     $(document).ready(function() {

            $('#table').DataTable();
            
        });
    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>
@stop
