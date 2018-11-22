@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Inventario
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Inventario</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Inventario </a></li>
        <li class="active">Index</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Inventario
                    </h4>
                    <div class="pull-right">
                    <a href="{{ route('admin.inventario.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Agregar Inventario</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($productos->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="tbInventario">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Inventario Disponible</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($productos as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->nombre_producto!!}</td>
                                    <td>{!! $inventario[$row->id]!!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>
                                            
                                            <a href="{{ route('admin.inventario.edit', $row->id) }}">
                                                <i class="fa fa-plus"></i>
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

 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

 
<script>





    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });

    $(document).ready(function(){

         $('#tbInventario').DataTable({
                      responsive: true,
                      pageLength: 10
                  });

         
    });

    


</script>
@stop
