@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('clientes/title.clientes') Inactivos
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1> @lang('clientes/title.clientes') Inactivos</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#">  @lang('clientes/title.clientes') Inactivos </a></li>
        <li class="active"> Inicio</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Clientes Inactivos
                    </h4>
                    
                </div>
                <br />
                <div class="panel-body">
                    @if ($clientes->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Grupo Cliente</th>
                                    <th>Estado Masterfile</th>
                                    <th>Estado Registro</th>
                                    <th>Marketing Sms </th>
                                    <th>Marketin Email</th>
                                    <th>Cliente Eliminado</th>
                                    <th>Creado</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                        </div>
                    @else
                        @lang('clientes/title.registros')
                    @endif   
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')

 <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>



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



<!-- Modal Direccion -->
 <div class="modal fade" id="activarUsuarioModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Activar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('admin/clientes/activar')}}" id="activarUsuarioForm" name="activarUsuarioForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="cliente_id" id="cliente_id" value="">

                            {{ csrf_field() }}
                            <div class="row">


                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Codigo Oracle Cliente </label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="cod_oracle_cliente" name="cod_oracle_cliente" type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                               

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Notas</label>

                                    <div class="col-sm-8">
                                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="res_activar"></div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendActivar" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->





<!-- Modal Direccion -->
 <div class="modal fade" id="rechazarUsuarioModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Rechazar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('admin/clientes/activar')}}" id="rechazarUsuarioForm" name="rechazarUsuarioForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="cliente_id" id="cliente_id" value="">

                            {{ csrf_field() }}
                            <div class="row">

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
                        <button type="button" class="btn  btn-primary sendRechazar" >Rechazar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->




<script>

 $(document).ready(function() {


        base=$('#base').val();
        
    var table =$('#table').DataTable( {
        "processing": true,
        "ajax": {
            "url": base+'/admin/clientes/datainactivos'
        }
    } );

    table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );


} );


    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>


<script type="text/javascript">



    $("#activarUsuarioForm").bootstrapValidator({
    fields: {
        cod_oracle_cliente: {
            validators: {
                notEmpty: {
                    message: 'Codigo Oracle  es Requerido'
                }
            },
            required: true,
            minlength: 3
        }
    }
});

      $("#rechazarUsuarioForm").bootstrapValidator({
    fields: {
        notas: {
            validators: {
                notEmpty: {
                    message: 'Codigo Oracle  es Requerido'
                }
            },
            required: true,
            minlength: 3
        }
    }
});
    


$(document).on('click', '.activarUsuario', function(){

    $('#cliente_id').val($(this).data('id'));

    $('#activarUsuarioModal').modal('show');

});





$('.sendActivar').click(function () {
    
    var $validator = $('#activarUsuarioForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        cliente_id=$('#cliente_id').val();
        cod_oracle_cliente=$('#cod_oracle_cliente').val();
        notas=$('#notas').val();

        $.ajax({
            type: "POST",
            data:{  cliente_id, cod_oracle_cliente, notas },
            url: base+"/admin/clientes/activar",
                
            complete: function(datos){   

                if (datos.responseText==0) {

                    $('.res_activar').html('<div class="label label-danger">Codigo Oracle Ya fue usado!</div>');

                }else{

                    $("#tr_"+cliente_id+'').html(datos.responseText);

                    $('#activarUsuarioModal').modal('hide');
                    
                    $('#cliente_id').val('');
                    $('#cod_oracle_cliente').val('');
                    $('#notas').val('');


                }  

                
        
            }
        });

    }

});

$(document).on('click', '.rechazarUsuario', function(){

    $('#cliente_id').val($(this).data('id'));

    $('#rechazarUsuarioModal').modal('show');

});



$('.sendRechazar').click(function () {
    
    var $validator = $('#rechazarUsuarioForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        cliente_id=$('#cliente_id').val();
        notas=$('#notas').val();

        $.ajax({
            type: "POST",
            data:{  cliente_id,  notas },
            url: base+"/admin/clientes/rechazar",
                
            complete: function(datos){ 

                if (datos.responseText=='true') {

                    $("#tr_"+cliente_id+'').fadeOut();

                    $('#rechazarUsuarioModal').modal('hide');
                
                    $('#cliente_id').val('');
                    
                    $('#notas').val('');
        
                }    

                
            }
        });

    }

});
    

</script>


@stop
