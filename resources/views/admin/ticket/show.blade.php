@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Detalle Ticket
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Detalle Ticket
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Ticket</li>
        <li class="active">Detalle</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       informacion de ticket
                    </h4> 
                </div>
                <div class="panel-body">

                    <input type="hidden" name="id_ticket" id="id_ticket" value="{{ $ticket->id }}">

                    
                        @if($ticket->estado_registro==1)
                        <div class="alert alert-info">
                            Actualmente el ticket se encuentra abierto
                        </div> 

                        @elseif($ticket->estado_registro==2)
                        <div class="alert alert-warning">
                            Actualmente el ticket se encuentra En Proceso
                        </div>
                        @else
                        <div class="alert alert-danger">
                            Actualmente el ticket se encuentra Cerrado
                        </div> 

                        @endif

                        <div class="col-sm-12">
                            <b>Ticket Abierto desde</b> {{$ticket->created_at}}
                        </div>

                        <div class="col-sm-6">

                            <h3><b>Ticket #</b> {{$ticket->id}}</h3>

                            <p><b>Usuario:</b> {{$ticket->first_name.' '.$ticket->last_name}}</p>
                            <p><b>Email:</b> {{$ticket->email}}</p>
                            <p><b>Origen:</b> {{$ticket->origen}}</p>
                            <p><b>Orden de Compra:</b>  <a class='btn btn-primary btn-xs' href='{{secure_url("admin/ordenes/".$ticket->orden."/detalle")}}' target='_blank'>Ver {{ $ticket->orden }} </a></p>

                            
                            
                        </div>


                         <div class="col-sm-6">

                            <h3><b>Departamento: </b> {{$ticket->nombre_departamento}}

                                <button class="btn btn-primary ticketdepartamento" data-estatus='1' data-id="{{$ticket->id}}">Reasignar</button>


                               </h3>
                            <h3><b>Urgencia:</b> {{$ticket->nombre_urgencia}}</h3>

                            <h3><b>Caso:</b> {{$ticket->nombre_caso}}</h3>


                            <h3><b>Estado :</b> 

                                @if($ticket->estado_registro==1)
                                    Abierto
                                @elseif($ticket->estado_registro==2)
                                    En Proceso

                                    @else
                                    Cerrado
                                @endif

                            @if($ticket->estado_registro==1)
                               <button class="btn btn-primary ticketstatus" data-estatus='1' data-id="{{$ticket->id}}">Cambiar</button> 
                            @elseif($ticket->estado_registro==2)
                                <button class="btn btn-warning ticketstatus" data-estatus='2' data-id="{{$ticket->id}}">Cambiar</button>
                                @else
                                <button class="btn btn-danger ticketstatus" data-estatus='0' data-id="{{$ticket->id}}">Cambiar</button>
                            
                                @endif


                            </h3>
                            
                        </div>

                        <div class="col-sm-12">
                            
                            <h3>Descripción </h3>

                            {!!$ticket->texto_ticket!!}

                        </div>

                        @if(is_null($ticket->archivo ) || $ticket->archivo=='' || $ticket->archivo==0)

                        <div class="col-sm-12">
                            
                            <h3>Sin archivos Adjuntos </h3>

                        </div>

                        @else

                        <div class="col-sm-12">
                            
                            <h3>Adjunto <a class="btn btn-info" target="_blank" href="{{secure_url('uploads/ticket/'.$ticket->archivo)}}">Ver Archivo</a> </h3>

                        </div>


                        @endif 

                        <div class="col-sm-12" style="margin-top: 1em;">

                            @include('admin.ticket.respuestas')

                        </div>
                   
                </div>

            </div>

        </div>
    </div>



<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Responder Ticket
                    </h4> 
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/ticket/'.$ticket->id.'/postcomentario') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                 <div class="row">
                    
                      <input type="hidden" id="id_ticket" name="id_ticket" value="{{$ticket->id}}">      

                    <div class="col-sm-12" style="margin-top:1em;">
                        

                        <div class="form-group {{ $errors->
                            first('titulo_ticket', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Titulo Ticket 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="titulo_ticket" name="titulo_ticket" class="form-control" placeholder="Titulo ticket"
                                       value="{!! old('titulo_ticket') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('titulo_ticket', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                    </div>


                        

                    <div class="col-sm-12" style="margin-top:1em;">
                        

                        <div class="form-group {{ $errors->
                            first('texto_ticket', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Contenido
                            </label>
                            <div class="col-sm-5">
                                 <textarea class="textarea form-control" name="texto_ticket" id="texto_ticket" placeholder="Contenido Ticket" rows="5" cols="10"></textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('texto_ticket', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-12" style="margin-top:1em; ">
                        

                        <div class="form-group {{ $errors->
                            first('archivo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Archivo
                            </label>
                            <div class="col-sm-5">
                                  <input type="file" id="archivo" name="archivo">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('archivo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>



                   

                      
                    

                        <div class="form-group col-sm-12" style="margin-top: 1em;">

                             <a class="btn btn-danger" href="{{secure_url('admin/ticket')}}">Volver</a>


                            <button type="submit" class="btn btn-success">Responder</button>
                           
                            
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                {!! Form::close() !!}
                   
                </div>
            </div>


        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary ">

                    <div class="panel-heading">
                        <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                           Historico del Ticket
                        </h4> 
                    </div>

                    <div class="panel-body">

                        <div class="row">

                        @foreach($historico as $h)

                            <div class="col-sm-12 alert alert-info">

                                <p>{{$h->notas}}</p>

                                <p>Actualizado por: {{$h->first_name.' '.$h->last_name}}</p>
                                <p>Fecha:  {{$h->created_at}}</p>
                                

                            </div>


                        @endforeach

                        </div>
                       
                    </div>
                </div>


            </div>
        </div>



    </div>
    



   <input type="hidden" value="{{secure_url('/')}}" id="base" name="base">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <!-- row-->
</section>


<!-- Modal Direccion -->
 <div class="modal fade" id="responderModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Actualiar ticket</h4>
                    </div>
                     <form method="POST" enctype="multipart/form-data" action="{{secure_url('admin/ticket/storerespuesta')}}" id="respuestaForm" name="respuestaForm" class="form-horizontal">
                    <div class="modal-body">
                        
                       

                            <input type="hidden" name="id_ticket_respuesta" id="id_ticket_respuesta" value="">
                            <input type="hidden" name="id_padre_respuesta" id="id_padre_respuesta" value="">

                            {{ csrf_field() }}

                            <div class="row">

                               <div class="col-sm-12" style="margin-top:1em;">

                        <div class="form-group {{ $errors->
                            first('titulo_ticket_respuesta', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Titulo Ticket 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="titulo_ticket_respuesta" name="titulo_ticket_respuesta" class="form-control" placeholder="Titulo ticket"
                                       value="{!! old('titulo_ticket_respuesta') !!}">
                            </div>
                            <div class="col-sm-7">
                                {!! $errors->first('titulo_ticket_respuesta', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                    </div>


                        

                    <div class="col-sm-12" style="margin-top:1em;">
                        

                        <div class="form-group {{ $errors->
                            first('texto_ticket_respuesta', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Contenido
                            </label>
                            <div class="col-sm-5">
                                 <textarea class="textarea form-control" name="texto_ticket_respuesta" id="texto_ticket_respuesta" placeholder="Contenido Ticket" rows="5" cols="10"></textarea>
                            </div>
                            <div class="col-sm-7">
                                {!! $errors->first('texto_ticket_respuesta', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-12" style="margin-top:1em; ">
                        

                        <div class="form-group {{ $errors->
                            first('archivo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Archivo
                            </label>
                            <div class="col-sm-5">
                                  <input type="file" id="archivo_respuesta" name="archivo_respuesta">
                            </div>
                            <div class="col-sm-7">
                                {!! $errors->first('archivo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>


                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type=" submit" class="btn  btn-primary " >Enviar</button>
                    </div>
                        </form>

                </div>
            </div>
        </div>

<!-- Modal Direccion -->



<!-- Modal Direccion -->
 <div class="modal fade" id="estatusModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Actualizar Ticket</h4>
                    </div>
                     <form method="POST" enctype="multipart/form-data" action="{{secure_url('admin/ticket/storerespuesta')}}" id="respuestaForm" name="respuestaForm" class="form-horizontal">
                    <div class="modal-body">

                            <input type="hidden" name="id_ticket_modal" id="id_ticket_modal" value="">
                            
                            {{ csrf_field() }}

                            <div class="row">

                               <div class="col-sm-12" style="margin-top:1em;">

                                <div class="form-group  {{ $errors->first('estatus_modal', 'has-error') }}">
                                    <label for="select21" class="col-sm-2 control-label">
                                        Estado de Ticket
                                    </label>
                                    <div class="col-sm-5">   
                                     <select id="estatus_modal" name="estatus_modal" class="form-control ">
                                        <option value="">Seleccione</option>
                                            
                                           
                                            <option value="{{ 1 }}"
                                                    >Abierto</option>

                                                    <option value="{{ 2 }}"
                                                    >En Proceso</option>

                                            <option value="{{ 0 }}"
                                                     >Cerrado</option>
                                           
                                    </select>
                                    <div class="col-sm-4">
                                        {!! $errors->first('estatus_modal', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                      
                                    </div>
                               
                                </div>


                                </div>


                                <div class="col-sm-12" style="margin-top:1em;">

                                <div class="form-group  {{ $errors->first('notas_modal', 'has-error') }}">
                                    <label for="select21" class="col-sm-2 control-label">
                                        Observación
                                    </label>
                                    <div class="col-sm-5">   
                                    <textarea class="form-control" name="notas_modal" id="notas_modal" cols="30" rows="10"></textarea>
                                    <div class="col-sm-4">
                                        {!! $errors->first('notas_modal', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                      
                                    </div>
                               
                                </div>


                                </div>





                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendestatus" >Cambiar</button>
                    </div>
                        </form>

                </div>
            </div>
        </div>

<!-- Modal Direccion -->


<!-- Modal Direccion -->
 <div class="modal fade" id="departamentoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Reasignar Ticket</h4>
                    </div>
                     <form method="POST" enctype="multipart/form-data" action="{{secure_url('admin/ticket/storerespuesta')}}" id="respuestaForm" name="respuestaForm" class="form-horizontal">
                    <div class="modal-body">
                        
                       

                            <input type="hidden" name="id_ticket_departamento" id="id_ticket_departamento" value="">
                            
                            {{ csrf_field() }}

                            <div class="row">

                               <div class="col-sm-12" style="margin-top:1em;">

                                <div class="form-group  {{ $errors->first('departamento_modal', 'has-error') }}">
                                    <label for="select21" class="col-sm-2 control-label">
                                        Reasignar a departamento
                                    </label>
                                    <div class="col-sm-5">   
                                     <select id="departamento_modal" name="departamento_modal" class="form-control ">
                                        <option value="">Seleccione</option>
                                                
                                            @foreach($departamentos as $d)

                                            <option value="{{ $d->id }}"
                                                    >{{$d->nombre_departamento}}</option>

                                            @endforeach

                                           
                                    </select>
                                    <div class="col-sm-4">
                                        {!! $errors->first('departamento', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                      
                                    </div>
                               
                                </div>






                                </div>


                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary senddepartamento" >Reasignar</button>
                    </div>
                        </form>

                </div>
            </div>
        </div>

<!-- Modal Direccion -->




@stop
@section('footer_scripts')




<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script>

    $('.ticketdepartamento').on('click', function(){

        $('#id_ticket_departamento').val($(this).data('id'));

        $('#departamentoModal').modal('show');


    });


    $('.senddepartamento').on('click', function(){

        base = $('#base').val();

        id = $('#id_ticket_departamento').val();

        departamento = $('#departamento_modal').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, departamento, _token},
            url: base+"/admin/ticket/departamento",
                
            complete: function(datos){     

                location.reload();
            }
        });

    });





    $('.ticketstatus').on('click', function(){

        $('#id_ticket_modal').val($(this).data('id'));

        $('#estatusModal').modal('show');


    });


    $('.sendestatus').on('click', function(){

        base = $('#base').val();

        id = $('#id_ticket_modal').val();

        notas = $('#notas_modal').val();

        estatus = $('#estatus_modal').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, estatus, _token, notas},
            url: base+"/admin/ticket/estatus",
                
            complete: function(datos){     

               location.reload();
            }
        });

    });






    $('.responder').on('click', function(){

        $('#id_ticket_respuesta').val($(this).data('ticket'));
        $('#id_padre_respuesta').val($(this).data('padre'));

        $('#responderModal').modal('show');


    });

    $('.sendRespuesta').click(function(){

        $('#respuestaForm').submit();
    })


    $('#tableAlmacen').DataTable();

    $('.marcar').click(function(){


        $('.cb').each(function(){
            $(this).attr("checked", "checked");
        });
    });



    $('.desmarcar').click(function(){

        $('.cb').each(function(){
            $(this).removeAttr('checked');
        });
    });



     $('.addFormaenvio').on('click', function(){

        base = $('#base').val();

        id_forma_envio = $('#id_forma_envio').val();

        id_almacen = $('#id_almacen').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_forma_envio, id_almacen, _token},
            url: base+"/admin/almacenes/"+id_almacen+"/addformenvio",
                
            complete: function(datos){     

                $(".listformasenvio").html(datos.responseText);

            }
        });

    });


     $(document).on('click','.delalmacenformaenvio',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_almacen = $(this).data('id_almacen');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_almacen, _token},
            url: base+"/admin/almacenes/"+id+"/delformaenvio",
                
            complete: function(datos){     

                $(".listformasenvio").html(datos.responseText);

            }
        });

    });







     $('.addFormapago').on('click', function(){

        base = $('#base').val();

        id_forma_pago = $('#id_forma_pago').val();

        id_almacen = $('#id_almacen').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_forma_pago, id_almacen, _token},
            url: base+"/admin/almacenes/"+id_almacen+"/addformapago",
                
            complete: function(datos){     

                $(".listformapago").html(datos.responseText);

            }
        });

    });


     $(document).on('click','.delalmacenformapago',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_almacen = $(this).data('idalmacen');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_almacen, _token},
            url: base+"/admin/almacenes/"+id+"/delformapago",
                
            complete: function(datos){     

                $(".listformapago").html(datos.responseText);

            }
        });

    });





     $('.addDespacho').on('click', function(){

        base = $('#base').val();

        id_city = $('#id_city').val();
        id_state = $('#id_state').val();
        id_barrio = $('#id_barrio').val();

        id_almacen = $('#id_almacen').val();

        _token = $('#_token').val();

         $.ajax({
            type: "POST",
            data:{ id_city, id_state, id_almacen, id_barrio, _token},
            url: base+"/admin/almacenes/"+id_almacen+"/adddespacho",
                
            complete: function(datos){     

                $(".listdespachos").html(datos.responseText);

            }
        });

    });


     $(document).on('click','.delalmacendespacho',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_almacen = $(this).data('idalmacen');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_almacen, _token},
            url: base+"/admin/almacenes/"+id+"/deldespacho",
                
            complete: function(datos){     

                $(".listdespachos").html(datos.responseText);

            }
        });

    });





    

      $('select[name="id_state"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/citiestodos/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="id_city"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="id_city"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="id_city"]').empty();
                    }
                });



       $('select[name="id_city"]').on('change', function() {
    var citiId = $(this).val();
    
    var base = $('#base').val();

    if(citiId) {

        $.ajax({
            url: base+'/configuracion/barriotodos/'+citiId,
            type: "GET",
            dataType: "json",
            success:function(data) {

                
                $('select[name="id_barrio"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="id_barrio"]').append('<option value="'+ key +'">'+ value +'</option>');

                });

            }
        });

    }else{
        $('select[name="id_barrio"]').empty();
    }
});






</script>






@stop