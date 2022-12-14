@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Lifemiles
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop



{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Lifemiles</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Lifemiles </a></li>
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
                       Lifemiles
                    </h4>
                    <div class="pull-right">
                    <a href="{{ secure_url('admin/lifemiles/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear Campaña Lifemiles</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($lifemiles->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table-lifemile">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Valor</th>
                                    <th>Minimo Compra</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Nombre Almacen</th>
                                    <th>Estado</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($lifemiles as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->nombre_lifemile!!}</td>
                                    <td>{!! $row->cantidad_millas !!}</td>
                                    <td>{!! $row->minimo_compra !!}</td>
                                    <td>{{$row->fecha_inicio}}</td>
                                    <td>{{$row->fecha_final}}</td>
                                    <td>
                                        @if($row->id_almacen==0)
                                        {{'Todos'}}
                                        @else
                                        {{$row->nombre_almacen}}
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <div class="btn-activo{{$row->id}}">
                                        @if($row->estado_registro =='0')
                                            <button data-id="{{$row->id}}"  class="desactivado btn btn-danger">
                                                Desactivado
                                            </button>
                                        @else
                                            <button data-id="{{$row->id}}"  class="activado btn btn-primary">
                                                Activo
                                            </button>
                                        @endif 
                                        </div>
                                    </td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>

                                            <a href="{{ secure_url('admin/lifemiles/'.$row->id.'/edit') }}">
                                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="editar abono"></i>
                                            </a>


                                            <a href="{{ secure_url('admin/lifemiles/'.$row->id) }}">
                                                <i class="livicon" data-name="eye-open" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="ver abono"></i>
                                            </a>


                                            <a href="{{ secure_url('admin/lifemiles/'.$row->id.'/upload') }}">
                                                <i class="livicon" data-name="upload" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="ver abono"></i>
                                            </a>

                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href="{{ secure_url('admin/lifemiles/'.$row->id.'/confirm-delete') }}" data-toggle="modal" data-target="#delete_confirm">
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

<input type="hidden" id="base" name="base" valeu="{{secure_url('/')}}">


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

    $(document).on('click','.desactivado', function(){
        id=$(this).data('id');
        base=$('#base').val();

        $.ajax({
            url: base+'/admin/lifemiles/'+id+'/desactivar',
            type: "GET",
            success:function(data) {

                $('.btn-activo'+id+'').html(data);

            }
        });

    });

    $(document).on('click','.activado',  function(){

        id=$(this).data('id');

        base=$('#base').val();

        $.ajax({
            url: base+'/admin/lifemiles/'+id+'/activar',
            type: "GET",
            success:function(data) {

                $('.btn-activo'+id+'').html(data);
            }
        });

    });


     $(document).ready(function() {

           // $('#table-lifemile').DataTable();
            
        });
    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>
@stop
