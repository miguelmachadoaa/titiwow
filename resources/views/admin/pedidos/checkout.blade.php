@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Pedidos
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop



{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Pedidos</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Pedidos </a></li>
        <li class="active">Listado</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">

         <form action="{{secure_url('admin/pedidos/procesar')}}" method="post" >

        <div class="col-lg-8">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Pedidos
                    </h4>
                    
                </div>
                <br />
                <div class="panel-body" style="padding-top: 0;">

                     <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                     <div class="row clientecompra" style="padding-top: 0;">
                         
                        @include('admin.pedidos.clientecompra')

                     </div> 

                </div>
            </div>
        </div>


         <div class="col-lg-4">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Detalle de Compra 
                    </h4>
                    
                </div>
                <br />
                <div class="panel-body" style="padding-top: 0;">
                     <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                     <div class="row" style="padding-top: 0;">
                         
                         
                         <div class="col-sm-12 listaorden">

                         @include('admin.pedidos.listaorden')
                        </div>
                     </div> 

                    
                </div>
            </div>
        </div>

</form>
        



    </div>    <!-- row-->
</section>

<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">


@stop




<div class="modal fade" id="AddAdressModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Dirección</h4>
                    </div>
                    <div class="modal-body">


                            <form method="POST" action="{{secure_url('clientes/storedir')}}" id="addDireccionForm" name="addDireccionForm" class="form-horizontal     ">

                     {{ csrf_field() }}

                     <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('titulo', 'has-error') }}">


                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="{!! old('titulo') !!}" >

                        {!! $errors->first('titulo', '<span class="help-block">:message</span>') !!}
                    
                    </div>
                            

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('cod_alpinista', 'has-error') }}">

                        <select id="state_id" name="state_id" class="form-control">
                            <option value="">Seleccione Departamento</option>   

                            @foreach($states as $state)

                                <option value="{{ $state->id }}"> {{ $state->state_name}} </option>

                            @endforeach

                        </select>

                        {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}

                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('city_id', 'has-error') }}">

                            <select id="city_id" name="city_id" class="form-control">

                                <option value="">Seleccione Ciudad</option>

                                @foreach($cities as $city)

                                    <option value="{{ $city->id }}">{{ $city->city_name}}</option>

                                @endforeach

                            </select>

                        {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}

                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                        <div class="col-sm-6" >
                            
                            <select id="id_estructura_address" name="id_estructura_address" class="form-control">

                                @foreach($estructura as $estru)

                                <option value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                @endforeach

                            </select>

                             {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div class="col-sm-6">
                            
                            <input type="text" id="principal_address" name="principal_address" class="form-control" placeholder="Ejemplo: 44 " value="{!! old('principal_address') !!}" >

                            {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                        </div>
                                    
                    </div>


                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('calle_address', 'has-error') }}">

                        <div class="col-sm-6" >

                            <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address') !!}" >

                            {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div class="col-sm-6">

                            <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address') !!}" >

                            {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('detalle_address', 'has-error') }}">

                        <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address') !!}" >

                        {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                    </div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

                        <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address') !!}" >

                        {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                    </div>

                     <div style="margin-left: 8%;" class="form-group col-sm-10 col-sm-offset-1 id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
                        <div class="" >
                            <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio') !!}" class="form-control">
                                <option value="">Seleccione Barrio</option>
                            </select>
                        </div>
                        {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
                    </div>



                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                        <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                        {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                    </div>

                           
                    <div class="clearfix"></div>

                    <div style="  margin-bottom: 1em;" class=" col-sm-10 col-sm-offset-1 {{ $errors->first('notas', 'has-error') }}">

                        <button class="btn btn-primary" type="submit" >Crear </button>

                    </div>

                </form>
                        
                       


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendAddress" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>







 <div class="modal fade" id="ClienteModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Seleccionar Cliente</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="confirm_id" id="confirm_id" value="">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Buscar
                                    </label>
                                    <div class="col-md-8" >
                                       <div class="input-group">
                                     
                                          <input type="text" name="buscar_cliente" id="buscar_cliente" class="form-control" placeholder="Buscar...">

                                           <span class="input-group-btn">

                                            <button class="btn btn-primary btn_buscar_cliente" type="button">Buscar</button>

                                          </span>
                                        </div><!-- /input-group -->


                                    </div>
                                </div>



                                <div class="col-sm-12 lista_clientes" style="overflow-y: scroll;height: 50%;">

                                    @include('admin.pedidos.listaclientes')

                                </div>


                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendConfirmar" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>





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

    $('#categoria').on('change', function(){

             base=$('#base').val();

             categoria=$('#categoria').val();

            $.get(base+'/admin/pedidos/'+categoria+'/datacategorias', function(data) {

                    $('.lista_de_productos').html(data);
            });
    });


      $('#marca').on('change', function(){

             base=$('#base').val();

             marca=$('#marca').val();

            $.get(base+'/admin/pedidos/'+marca+'/datamarcas', function(data) {

                    $('.lista_de_productos').html(data);
            });
    });


      $('.btn_buscar').on('click', function(){

             base=$('#base').val();

             buscar=$('#buscar').val();

            $.get(base+'/admin/pedidos/'+buscar+'/databuscar', function(data) {

                    $('.lista_de_productos').html(data);
            });
    });


       $('.btn_buscar_cliente').on('click', function(){

             base=$('#base').val();

             buscar=$('#buscar_cliente').val();

            $.get(base+'/admin/pedidos/'+buscar+'/databuscarcliente', function(data) {

                    $('.lista_clientes').html(data);
            });
    });




      $('.addproducto').on('click', function(){

             base=$('#base').val();

             id=$(this).data('id');

            $.get(base+'/admin/pedidos/'+id+'/addtocart', function(data) {

                    $('.listaorden').html(data);
            });
    });



      $(document).on('click','.seleccionarCliente', function(){

             $('#ClienteModal').modal('show');

             
    });


    $(document).on('click','.asignaCliente',function(){

        var base = $('#base').val();

         id=$(this).data('id');
             
            $.get(base+'/admin/pedidos/'+id+'/asignacliente', function(data) {

                    $('#ClienteModal').modal('hide');

                    $('.clientecompra').html(data);
            });
             
    });







    $(document).on('change','.cantidadcarrito', function(){

        base=$('#base').val();

        id=$(this).data('id');

        cantidad=$(this).val();

        if (cantidad==0) {


        }else{

            $.get(base+'/admin/pedidos/'+id+'/updatecart/'+cantidad, function(data) {

                    $('.listaorden').html(data);
            });


        }

    });





     $('select[name="id_cliente"]').on('change', function() {
            
                var idcliente = $(this).val();

                var base = $('#base').val();

                    if(idcliente) {

                        $.ajax({
                            url: base+'/admin/pedidos/'+idcliente+'/getdirecciones',
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                alert('1');

                                $('#id_direccion"]').empty();

                                $('#id_direccion').html(data);


                            }
                        });


                    }else{
                        $('select[name="id_direccion"]').empty();
                    }
                });










</script>
@stop
