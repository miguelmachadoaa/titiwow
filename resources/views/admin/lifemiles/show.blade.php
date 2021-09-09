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
        <li class="active">Detalle </li>
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
                    
                </div>
                <br />
                <div class="panel-body">
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table">
                            <tbody>

                                <tr>
                                    <td>Id</td>
                                    <td>{{$lifemile->id}}</td>
                                </tr>

                                <tr>
                                    <td>Nombre</td>
                                    <td>{{$lifemile->nombre_lifemile}}</td>
                                </tr>

                                <tr>
                                    <td>Millas</td>
                                    <td>{{$lifemile->cantidad_millas}}</td>
                                </tr>

                                <tr>
                                    <td>Fecha Limite</td>
                                    <td>{{$lifemile->fecha_final}}</td>
                                </tr>

                                <tr>
                                    <td>Minimo Compra</td>
                                    <td>{{$lifemile->minimo_compra}}</td>
                                </tr>

                                <tr>
                                    <td>Fecha Inicio</td>
                                    <td>{{$lifemile->fecha_inicio}}</td>
                                </tr>

                                <tr>
                                    <td>Fecha Final</td>
                                    <td>{{$lifemile->fecha_final}}</td>
                                </tr>


                                <tr>
                                    <td>Nombre Almacen</td>
                                    <td>{{$lifemile->nombre_almacen}}</td>
                                </tr>



                                <tr>
                                    <td>Estado</td>
                                    <td>{{$lifemile->estado_registro}}</td>
                                </tr>

                                <tr>
                                    <td>Creado</td>
                                    <td>{{$lifemile->created_at}}</td>
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
                       Codigos
                    </h4>
                    
                </div>
                <br />
                <div class="panel-body">

                <table class="table table-striped" id="table-codigos">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Parnert</th>
                            <th>Miles</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Final</th>
                            <th>Estatus</th>
                            <th>Codigo</th>
                            <th>Prueba </th>
                            <th>Actualizado </th>
                            <th>Fecha</th>
                            <th>Estado Registro </th>
                            <th>Orden </th>
                        </tr>
                    </thead>
                    <tbody>
                    

                    @foreach($codigos as $h)

                        <tr>
                            <td>{{$h->id}}</td>
                            <td>{{$h->parnert}}</td>
                            <td>{{$h->miles}}</td>
                            <td>{{$h->fecha_inicio}}</td>
                            <td>{{$h->fecha_final}}</td>
                            <td>{{$h->estatus}}</td>
                            <td>{{$h->code}}</td>
                            <td>{{$h->prueba}}</td>
                            <td>{{$h->estatus}}</td>
                            <td>{{$h->created_at}}</td>
                            <td>
                                @if($h->estado_registro=='1')

                                {{'No Asigando '}}
                                @else
                                {{' Asigando '}}
                                @endif
                            </td>
                            <td>{{$h->id_orden}}</td>
                        </tr>

                    @endforeach

                    </tbody>
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


<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>

 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">

<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>



<script>


     $(document).ready(function() {
        
        $('#table-codigos').dataTable({
         responsive: true
        });

    });

    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>
@stop
