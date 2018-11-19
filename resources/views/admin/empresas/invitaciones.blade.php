@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Empresa - Invitaciones
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Empresa</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Empresa </a></li>
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
                       Empresa
                    </h4>
                    <div class="pull-right">
                    <a href="#" data-id="{{ $empresa->id }}"  class="btn btn-sm btn-default addAmigo"><span class="glyphicon glyphicon-plus"></span> Agregar Invitacion</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    
                    <br>
                    <div class="table-responsive" id="table_amigos">
                    @if ($invitaciones->count() >= 1)
                        

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <!--th>Enlace</th-->
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($invitaciones as $row)
                                <tr>
                                    <td>{!! $row->id !!}</td>
                                    <td>{!! $row->nombre_amigo!!}</td>
                                    <td>{!! $row->apellido_amigo !!}</td>
                                    <td>{!! $row->email_amigo !!}</td>
                                    <!--td><a href=" {!! url('/').'/registroafiliado/'.$row->token  !!}  ">Enlace</a></td-->
                                    <td>
                                            
                                            <a class="delAmigo"  data-id="{{ $row->id }}" data-url="{{ url('admin/empresas/delamigo') }}" href="#" data-toggle="modal" >
                                            <i class="fa fa-trash"></i>
                                           
                                             </a>


                                              

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    @else
                        No se encontraron registros
                    @endif  
                    </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




<div class="modal fade" id="addAmigoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Amigo</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{url('storeamigo')}}" id="addAmigoForm" name="addAmigoForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ url('/') }}">
                            <input type="hidden" name="id_empresa" id="id_empresa" value="">

                            {{ csrf_field() }}

                            <div class="row">

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Nombre</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="nombre" name="nombre" type="text" placeholder="Nombre" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Apellido</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="apellido" name="apellido" type="text" placeholder="Apellido" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label" for="nombre_producto">Email</label>

                                    <div class="col-sm-8">
                                        <input style="margin: 4px 0;" id="email" name="email" type="text" placeholder="Email" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendAmigo" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>


    <div class="modal fade" id="delAmigoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Amigo</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="del_id" id="del_id" value="" >
                        <input type="hidden" name="del_url" id="del_url" value="" >
                        
                        <h3> Esta Seguro de eliminar el registro?</h3>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary deleteAmigo" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>




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

<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

<script>
    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });


    $(document).on('click', '.addAmigo', function(e){
            e.preventDefault();

            $('#id_empresa').val($(this).data('id'));

            $("#addAmigoModal").modal('show');

        });

        $(document).on('click', '.delAmigo', function(e){
            e.preventDefault();

            $('#del_id').val($(this).data('id'));
            $('#del_url').val($(this).data('url'));

                $("#delAmigoModal").modal('show');

        });





$("#addAmigoForm").bootstrapValidator({
    fields: {
        nombre: {
            validators: {
                notEmpty: {
                    message: 'Nombre es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        apellido: {
            validators: {
                notEmpty: {
                    message: 'Apellido es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        email: {
                validators: {
                    emailAddress: {
                        message: 'Ingrese Un Email Valido'
                    }, 
                    notEmpty: {
                    message: 'Email es Requerido'
                    }
                }
            }
        
    }
});


$('.sendAmigo').click(function () {
    
    var $validator = $('#addAmigoForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        id_empresa=$("#id_empresa").val();
        nombre=$("#nombre").val();
        apellido=$("#apellido").val();
        email=$("#email").val();
        
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{nombre, apellido, email, id_empresa},
            url: base+"/admin/empresas/storeamigo",
                
            complete: function(datos){     

                $("#table_amigos").html(datos.responseText);

                $('#addAmigoModal').modal('hide');

                $("#nombre").val('');

                $("#apellido").val('');

                $("#email").val('');

            
            }
        });


    }

});


$('.deleteAmigo').click(function () {
    
        id=$("#del_id").val();
        url=$("#del_url").val();
               
        var base = $('#base').val();

        $.ajax({
            type: "POST",
            data:{id},
            url: url,
                
            complete: function(datos){     

                $("#table_amigos").html(datos.responseText);

                $('#delAmigoModal').modal('hide');
            
            }
        });

});











</script>
@stop
