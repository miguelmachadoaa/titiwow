@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Pedidos
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />


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

                         @include('admin.pedidos.listaordencheckout')
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







<div class="modal fade" id="AddClienteModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Agregar Dirección</h4>
                    </div>
                    <div class="modal-body">

                         <form action="{{ secure_url('admin/pedidos/postregistro') }}" method="POST" id="reg_form" name="reg_form">

                        <div class="row">
                            
                            <div class="col-sm-6 " >


                            <h4 class="text-primary">Datos del Cliente</h4>
                         
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nombre"
                           value="{!! old('first_name') !!}" >
                    {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Apellido"
                           value="{!! old('last_name') !!}" >
                    {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('id_type_doc', 'has-error') }}">
                    <div class="" >
                        <select id="id_type_doc" name="id_type_doc" class="form-control {{ $errors->first('id_type_doc', 'has-error') }}">
                            <option value="">Seleccione Tipo de Documento</option>     
                            @foreach($t_documento as $tdoc)
                                <option value="{{ $tdoc->id }}" {{ (old("id_type_doc") == $tdoc->id ? "selected":"") }}>{{ $tdoc->abrev_tipo_documento}} - {{ $tdoc->nombre_tipo_documento}}</option>
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first('id_type_doc', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('doc_cliente', 'has-error') }}">
                    <input type="text" class="form-control" id="doc_cliente" name="doc_cliente" placeholder="Nro de Documento"
                           value="{!! old('doc_cliente') !!}" >
                    {!! $errors->first('doc_cliente', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <input type="email" class="form-control" id="Email" name="email" placeholder="Email"
                           value="{!! old('email') !!}" >
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('telefono_cliente', 'has-error') }}">
                    <input type="number" class="form-control" id="telefono_cliente" name="telefono_cliente" placeholder="Teléfono"
                           value="{!! old('telefono_cliente') !!}" >
                    {!! $errors->first('telefono_cliente', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <input type="password" class="form-control" id="Password1" name="password" placeholder="Contraseña">
                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                    <input type="password" class="form-control" id="Password2" name="password_confirm"
                           placeholder="Confirmar Contraseña">
                    {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>


                </div>
                <div class="col-sm-6">
                    


                <h4 class="text-primary">Dirección</h4>
                <div class="form-group {{ $errors->first('state_id', 'has-error') }}">
                    <div class="" >
                        <select id="state_id" name="state_id" value="{!! old('state_id') !!}" class="form-control">
                            <option value="">Seleccione Departamento</option>     
                            @foreach($states as $state)
                            <option value="{{ $state->id }}">
                                    {{ $state->state_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first('state_id', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('city_id', 'has-error') }}">
                    <div class="" >
                        <select id="city_id" name="city_id" value="{!! old('city_id') !!}" class="form-control">
                            <option value="">Seleccione Ciudad</option>
                        </select>
                    </div>
                    {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <div style="padding: 0;" class="form-group  col-sm-6  col-xs-6 {{ $errors->first('id_estructura_address', 'has-error') }}">
                            <select id="id_estructura_address" name="id_estructura_address" value="{!! old('id_estructura_address') !!}" class="form-control">
                                @foreach($estructura as $estru)
                                <option value="{{ $estru->id }}">
                                {{ $estru->abrevia_estructura}} - {{ $estru->nombre_estructura}} </option>
                                @endforeach
                            </select>
                        {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div style="padding-right: 0;" class="form-group col-sm-6 col-xs-6  {{ $errors->first('principal_address', 'has-error') }}">
                        <div class="input-group">
                            <!--span class="input-group-addon azul" id="basic-addon2">Principal</span-->
                            <input type="text" class="form-control" id="principal_address" name="principal_address" style="font-style:italic" value="{!! old('principal_address') !!}" placeholder="Ejemplo: 100" aria-describedby="basic-addon2">
                        </div>
                        {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div style="padding: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('secundaria_address', 'has-error') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" id="secundaria_address" name="secundaria_address" value="{!! old('secundaria_address') !!}"  placeholder="Ejemplo: #21" aria-describedby="basic-addon3">
                    </div>
                    {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div style="padding-right: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('edificio_address', 'has-error') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" value="{!! old('edificio_address') !!}" id="edificio_address" name="edificio_address" placeholder="Ejemplo: -14" aria-describedby="basic-addon4">
                    </div>
                    {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                <div class="form-group {{ $errors->first('detalle_address', 'has-error') }}">
                    <input type="text" class="form-control" value="{!! old('detalle_address') !!}" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior"
                           value="{!! old('detalle_address') !!}" >
                    {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group barrio_address {{ $errors->first('barrio_address', 'has-error') }}">
                    <input type="text" class="form-control" value="{!! old('barrio_address') !!}" id="barrio_address" name="barrio_address" placeholder="Barrio"
                           value="{!! old('barrio_address') !!}" >
                    {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}
                </div>


                <div style="padding: 0;" class="form-group col-sm-12 id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
                    <div class="" >
                        <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio') !!}" class="form-control">
                            <option value="">Seleccione Barrio</option>
                        </select>
                    </div>
                    {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
                </div>


                <div class="clearfix"></div>

                    {!! $errors->first('convenio', '<span class="help-block">:message</span>') !!}
                </div-->
                <div class="clearfix"></div>
                <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="chkalpinista" id="chkalpinista" value="1"> ¡Soy Alpinista! <small>(Opcional)</small> </a>
                    </label>
                </div>
                <div class="form-group {{ $errors->first('cod_alpinista', 'has-error') }}">
                    <input type="text" class="form-control" value="{!! old('cod_alpinista') !!}" id="cod_alpinista" name="cod_alpinista" placeholder="Código de Alpinista" value="{!! old('cod_alpinista') !!}" >
                    <div class="res_cod_alpinista"></div>

                    {!! $errors->first('cod_alpinista', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="clearfix"></div>
                
                
                </div>

                <div class="col-sm-12">
                    

                
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <button id="btnsubmit" name="btnsubmit" type="button" class="btn btn-block btn-primary">Registrarse</button>
                        </div>
                        <div class="col-sm-6">
                            <a class="btn btn-block btn-danger" href="#">Cancelar</a>
                        </div>
                    </div>
                </div>


                </div>
            </div>

            </form>


                    </div>
                    <!--div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendAddress" >Agregar</button>
                    </div-->
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

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/register_custom.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>


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





        $(document).on('click','.addCliente', function(){

             $('#AddClienteModal').modal('show');

             
    });













































$(document).ready(function(){


        $('#Password1').on('focus', function() {
            $('#Password1').val('');
          });


        $('#Password2').on('focus', function() {
            $('#Password2').val('');
          });


        $('#cod_alpinista').hide();
        $('#chkalpinista' ).on( 'click', function() {
            if( $(this).is(':checked') ){
                $('#cod_alpinista').show();
            } else {
                $('#cod_alpinista').hide();
                $('#cod_alpinista').val("");
            }
        });

        $('#cod_alpinista').change(function(){
                $('#btnsubmit').removeAttr('disabled');             
        })

        $(document).on('click','#btnsubmit', function(e){

            codigo=0;

            e.preventDefault();

            base=$('#base').val();

            _token=$('input[name="_token"]').val();

            convenio=$('#convenio').val();

            if (convenio!='' && convenio!=undefined) {

                $.post('postconveniosregistro', { convenio, _token}, function(data) {

                    // alert(data+'d');

                    if (data==1) {

                        $('.res_cod_alpinista').html('');


                        var $validator = $('#reg_form').data('bootstrapValidator').validate();


                        if( $('#chkalpinista').is(':checked') ) {


                            if ($('#cod_alpinista').val()!='') {


                                if ($validator.isValid()) {


                                    $("#reg_form")[0].submit();

                                }

                            }else{


                                $('.res_cod_alpinista').html('<span class="help-block">Código de Alpinista es requerido</span>');

                                //$('#btnsubmit').attr('disabled', '1');
                            }

                        }else{


                            if ($validator.isValid()) {

                               $("#reg_form")[0].submit();
                                   
                            }

                        }   

                    }else{

                        $('.res_convenio').html('<span class="help-block">El Código de convenio no existe</span>');

                        $('#convenio').val('');
                    }

                });

            }else{

                $('.res_cod_alpinista').html('');

                var $validator = $('#reg_form').data('bootstrapValidator').validate();

                if( $('#chkalpinista').is(':checked') ) {

                    if ($('#cod_alpinista').val()!='') {

                        if ($validator.isValid()) {

                            $("#reg_form")[0].submit();

                        }

                    }else{

                        $('.res_cod_alpinista').html('<span class="help-block">Código de Alpinista es requerido</span>');

                        //$('#btnsubmit').attr('disabled', '1');

                    }

                }else{


                    if ($validator.isValid()) {

                       $("#reg_form")[0].submit();
                           
                    }
                }

            }

            //alert(codigo);

        });


        $("#state_id").select2();
        $("#city_id").select2();
        $("#id_barrio").select2();
        $("#id_type_doc").select2();
        $("#id_estructura_address").select2();
        //Inicio select región
                        
            //inicio select ciudad
            $('select[name="state_id"]').on('change', function() {
                var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/registro/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key+'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });



            $('select[name="city_id"]').on('change', function() {
                var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {

                        $.ajax({
                            url: base+'/configuracion/barrios/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="id_barrio"]').empty();

                                console.log(JSON.stringify(data).length);

                                if (JSON.stringify(data).length>25) {

                                    $('.barrio_address').addClass('hidden');
                                    $('·barrio_address').val(' ');

                                    $('.id_barrio').removeClass('hidden');

                                }else{

                                    $('.barrio_address').removeClass('hidden');

                                    $('#id_barrio').val(0);

                                    $('.id_barrio').addClass('hidden');

                                }

                                $.each(data, function(key, value) {
                                    $('select[name="id_barrio"]').append('<option value="'+ key+'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{

                        $('select[name="id_barrio"]').empty();

                    }
                });

            //fin select ciudad
        });






















</script>
@stop
