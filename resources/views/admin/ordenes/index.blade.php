@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ordenes
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ordenes</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ordenes</a></li>
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
                       Ordenes
                    </h4>
                    <div class="pull-right">
                  <!--  <a href="{{ route('admin.ordenes.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear categoria</a>-->
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @if ($ordenes->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="tbOrdenes">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Referencia</th>
                                    <th>Cliente</th>
                                    <th>Forma de Envio</th>
                                    <th>Forma de Pago</th>
                                    <th>Total</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ordenes as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->referencia!!}</td>
                                    <td>{!! $row->first_name.' '.$row->last_name !!}</td>
                                    <td>{!! $row->nombre_forma_envios !!}</td>
                                    <td>{!! $row->nombre_forma_pago !!}</td>
                                    <td>{!! number_format($row->monto_total,2) !!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>

                                            <a href="{{ route('admin.ordenes.detalle', $row->id) }}">
                                                <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Detalle"></i>
                                            </a>

                                            <button data-id="{{ $row->id }}" class="btn btn-xs btn-info confirmar" > Confirmar </button>


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



<!-- Modal Detalle -->


  
<!-- Modal Direccion -->
 <div class="modal fade" id="confirmarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Nickname Direccion</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="nickname_address" name="nickname_address" type="text" placeholder="Nickname Direccion" class="form-control">
                                    </div>
                                </div>


                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Estatus Ordenes
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="country_id" name="country_id" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            @foreach($estatus_ordenes as $est)
                                            <option value="{{ $est->id }}"
                                                    @if($est->id == old('country_id')) selected="selected" @endif >{{ $est->estatus_nombre}}</option>
                                            @endforeach
                                          
                                        </select>
                                    </div>
                                </div>

                                            </select>
                                    </div>
                                </div>

                                



                                
                                

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Calle </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="calle2_address" name="calle2_address" type="text" placeholder="" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Postal</label>

                                    <div class="col-sm-8">
                                     <input style="margin: 4px 0;" id="codigo_postal_address" name="codigo_postal_address" type="text" placeholder="Codigo Postal" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Telefono</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="telefono_address" name="telefono_address" type="text" placeholder="Telefono" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Notas</label>

                                    <div class="col-sm-8">
                                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control"></textarea>
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendDireccion" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->


<!-- Modal Direccion -->




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
<script>
    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>




    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>
        $('#tbOrdenes').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#tbOrdenes').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

       </script>


@stop
