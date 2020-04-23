@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Detalle Almacen
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
        Detalle Almacen
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Almacens</li>
        <li class="active">Gestionar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Configurar Despacho, Formas de Pago y Forma de Envio
                    </h4> 
                </div>
                <div class="panel-body">

                    <input type="hidden" name="id_almacen" id="id_almacen" value="{{ $almacen->id }}">

                        
                    <fieldset>
                        <legend>Despacho</legend>

                        <div class="row">
                            
                             <div class="form-group col-sm-8 {{ $errors->first('id_state', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Zonas de Despacho
                                </label>
                                <div class="col-sm-4">   
                                 <select id="id_state" name="id_state" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    <option value="0">Todos</option>
                                    @foreach($states as $state)

                                        <option value="{{$state->id}}">{{$state->state_name}}</option>

                                    @endforeach

                                </select>

                                  {!! $errors->first('id_state', '<span class="help-block">:message</span> ') !!}
                                </div>



                                <div class="col-sm-4">   
                                 <select id="id_city" name="id_city" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    <option value="0">Todos</option>
                                    @foreach($states as $state)

                                        <option value="{{$state->id}}">{{$state->state_name}}</option>

                                    @endforeach

                                </select>

                                  {!! $errors->first('id_city', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" role="button" class="btn btn-success addDespacho" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listdespachos">

                            @include('admin.almacenes.listdespachos')
                            


                        </div>
                    </fieldset>

                        <hr>

                     <fieldset>
                        <legend>Formas de Pago </legend>

                        <div class="row">
                            
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_forma_pago', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Formas de Pago
                                </label>
                                <div class="col-sm-8">   
                                 <select id="id_forma_pago" name="id_forma_pago" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($formas_pago as $fp)

                                        <option value="{{$fp->id}}">{{$fp->nombre_forma_pago}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_forma_pago', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" role="button" class="btn btn-success addFormapago" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listformapago">

                            @include('admin.almacenes.listformaspago')
                            


                        </div>
                    </fieldset>


                   

                    <hr>

                    <fieldset>
                        <legend>Formas de Envio </legend>

                        <div class="row">
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_forma_envio', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Formas de Envio
                                </label>
                                <div class="col-sm-8">   

                                 <select id="id_forma_envio" name="id_forma_envio" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($formas_envio as $fe)

                                        <option value="{{$fe->id}}">{{$fe->nombre_forma_envios}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_forma_envio', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>

                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" class="btn btn-success addFormaenvio" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listformasenvio">

                          @include('admin.almacenes.listformasenvio')


                        </div>
                    </fieldset>

                   
                </div>
            </div>













          


        </div>
    </div>

    












    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Productos en el Almacen
                    </h4>
                </div>
                <div class="panel-body">
                    
                        
                            <h3>Productos Activos en el Almacen</h3>
                            
                            <table class="table table-striped" id="tableAlmacen">
                                
                                <thead>
                                    <tr>
                                       
                                        <th>
                                            Id
                                        </th>
                                        <th>
                                            Imagen
                                        </th>
                                        <th>
                                            Nombre
                                        </th>
                                        <th>Referencia</th>
                                        <th>
                                            Referencia Sap
                                        </th>

                                        <th>
                                            Inventario
                                        </th>

                                       
                                    </tr>
                                </thead>
                            

                        <tbody>

                        @foreach($productos as $p)

                        <tr>
                            <td>
                                {{$p->id}}
                            </td>

                            <td>
                                <img style="width: 60px;" src="{{secure_url('uploads/productos/'.$p->imagen_producto)}}" alt="img">
                            </td>

                            <td>
                                {{$p->nombre_producto}}
                            </td>
                            <td>
                                {{$p->referencia_producto}}
                            </td>

                            <td>
                                {{$p->referencia_producto_sap}}
                            </td>
                            <td>
                                @if(isset($inventario[$p->id][$almacen->id]))

                                    {{$inventario[$p->id][$almacen->id]}}

                                @else

                                    {{'0'}}

                                @endif
                            </td>

                           
                        </tr>



                       
    
                        @endforeach

                        </tbody>
    
                        </table>

                     
                   
                </div>
            </div>
        </div>
    </div>

   <input type="hidden" value="{{secure_url('/')}}" id="base" name="base">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <!-- row-->
</section>

@stop
@section('footer_scripts')

<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script>


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



        id_almacen = $('#id_almacen').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_city, id_state, id_almacen, _token},
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
                            url: base+'/configuracion/cities/'+stateID,
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



</script>


@stop