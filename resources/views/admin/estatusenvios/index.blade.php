@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Estatus Envios
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Estatus Envios</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Estatus Envios </a></li>
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
                       Estatus Envios
                    </h4>
                    <div class="pull-right">
                    <a href="{{ secure_url('admin/estatusenvios/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear Estatus Envios</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($estatus->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($estatus as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->estatus_envio_nombre!!}</td>
                                    <td>{!! $row->estatus_envio_descripcion !!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>
                                            
                                            



                                            <a href="{{ secure_url('admin/estatusenvios/'.$row->id.'/edit') }}">
                                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Editar Estado de Envio"></i>
                                            </a>



                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href="{{ secure_url('admin/estatusenvios/'.$row->id.'/confirm-delete') }}" data-toggle="modal" data-target="#delete_confirm">
                                            <i class="livicon" data-name="remove-alt" data-size="18"
                                                data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                title="Eliminar"></i>
                                             </a>


                                              

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        No se encontraron registros
                    @endif   
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
