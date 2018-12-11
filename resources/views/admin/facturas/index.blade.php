@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Carga de Facturas Masivas
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Facturas Masivas</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Facturas Masivas</a></li>
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
                       Carga de Facturas Masivas
                    </h4>
                    <div class="pull-right">
                        <a href="{{ secure_url('admin/facturasmasivas/create') }}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span> Cargar Facturas</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($facturas->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Orden de Compra</th>
                                    <th>Factura</th>
                                    <th>Estado Cargada</th>
                                    <th>ID Pedido</th>
                                    <th>Creado</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($facturas as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->orden_compra !!}</td>
                                    <td>{!! $row->factura !!}</td>
                                    @if($row->estatus_factura == 1)
                                        <td><span class="label label-sm label-success">Actualizada</span></td>
                                    @elseif($row->estatus_factura == 2)
                                        <td><span class="label label-sm label-danger">No Existe la Orden</span></td>
                                    @endif
                                    @if(empty($row->id_orden))
                                    <td>No Existe</td>
                                    @else
                                    <td>{!! $row->id_orden !!}</td>
                                    @endif
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    
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
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#table').DataTable();
            
        });
    </script>
@stop
