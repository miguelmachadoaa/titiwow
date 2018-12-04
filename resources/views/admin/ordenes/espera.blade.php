@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ordenes Aprobadas
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ordenes En Espera</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ordenes En Espera</a></li>
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
                       Ordenes En Espera
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
                                    <th>Codigo Oracle</th>
                                    <th>Factura</th>
                                    <th>Tracking</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ordenes as $row)
                                <tr id="{!! $row->id !!}">
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->referencia!!}</td>
                                    <td>{!! $row->first_name.' '.$row->last_name !!}</td>
                                    <td>{!! $row->nombre_forma_envios !!}</td>
                                    <td>{!! $row->nombre_forma_pago !!}</td>
                                   
                                    <td>{!! number_format($row->monto_total,2) !!}</td>

                                    <td>{!! $row->ordencompra!!}</td>
                                    <td>{!! $row->factura!!}</td>
                                    <td>{!! $row->tracking!!}</td>
                                    <td>{!! $row->created_at->diffForHumans() !!}</td>
                                    <td>

                                            <a class="btn btn-primary btn-xs" href="{{ route('admin.ordenes.detalle', $row->id) }}">
                                                ver detalles
                                            </a>


                                            <div style="display: inline-block;" class="recibir_{{ $row->id }}">
                                            <button data-id="{{ $row->id }}"  data-codigo="{{ $row->factura }}"  data-estatus="{{ $row->estatus }}" class="btn btn-xs btn-info recibir" > Recibir </button></div>



                                            <!--<div style="display: inline-block;" class="pago_{{ $row->id }}">  

                                            <button data-id="{{ $row->id }}" class="btn btn-xs btn-success pago" > {{ $row->estatus_pago_nombre }} </button></div>-->


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
 <div class="modal fade" id="recibirOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Recibir Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="facturarOrdenForm" name="facturarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="recibir_id" id="recibir_id" value="">

                            {{ csrf_field() }}
                            <div class="row">


                                <!--<div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Numero Factura  </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="num_factura" name="num_factura" type="text" placeholder="" class="form-control">
                                    </div>
                                </div>-->
                               

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
                        <button type="button" class="btn  btn-primary sendRecibir" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->







@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')


<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>

 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">

<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

  <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script>





/*$("#facturarOrdenForm").bootstrapValidator({
    fields: {
        facturarOrdenForm: {
            validators: {
                notEmpty: {
                    message: 'Codigo Aprobacion  es Requerido'
                }
            },
            required: true,
            minlength: 3
        }
    }
});*/


 

 $('#tbOrdenes').on('click','.recibir', function(){

   
        $('#recibir_id').val($(this).data('id'));

       // $('#num_factura').val($(this).data('codigo'));


            $("#recibirOrdenModal").modal('show');
        });






 $('.sendRecibir').click(function () {
    
//    var $validator = $('#facturarOrdenForm').data('bootstrapValidator').validate();

   // if ($validator.isValid()) {

        base=$('#base').val();
        id=$('#recibir_id').val();
       /// codigo=$('#num_factura').val();
        notas=$('#notas').val();


        $.ajax({
            type: "POST",
            data:{ base, id,  notas },
            url: base+"/admin/ordenes/recibir",
                
            complete: function(datos){     

                //$(".facturar_"+id+'').html(datos.responseText);
                $("#"+id+'').remove();

                $('#recibirOrdenModal').modal('hide');
                
                $('#recibir_id').val('');
               // $('#num_factura').val('');
                $('#notas').val('');
        
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


   // }

});



        $('#tbOrdenes').DataTable({
                      responsive: true,
                      pageLength: 10,
                      "order": [[ 0, 'desc' ]]
                  });
                  $('#tbOrdenes').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

       </script>


@stop