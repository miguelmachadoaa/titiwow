@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Pedidos
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    
@stop

{{-- Content --}}
@section('content')
<section class="content-header" style="margin-bottom:0;">
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

    <div  data-cart="{{json_encode($cart)}}" ></div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row sinmargen" style="padding:0;">

    <div class="col-sm-4" style="padding:0;">
            <div class="panel panel-primary ">
                <div class="panel-body" style="padding-top: 0;">

                <div class="row">

               
                </div>

                     <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                     <div class="row clientecompra table-responsive" style="">

                        @include('admin.pedidos.clientecompra')

                     </div> 

                     <div class="row" style="padding-top: 0;">

                         <div class="col-sm-12 listaorden table-responsive">

                            @include('admin.pedidos.listaorden')

                        </div>
                     </div> 
                    
                </div>
            </div>
        </div>



        <div class="col-sm-8" style="padding:0;">



            <div class="panel panel-primary ">
               
                <div class="panel-body" style="padding-top: 0;">

                    @if(isset($cart['id_cliente']))


                     <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                     <div class="row" style="padding-top: 0;">

                      <!--div class="col-sm-12">
                          
                          {{json_encode($cart)}}

                      </div-->
                         
                         <div class="col-sm-12" style="padding:0;">

                            <div class="row">

                            <input type="hidden"  name="almacen" id="almacen" value="{{$cart['id_almacen']}}">

                            <div class="col-sm-12 mb-1">


                                <div class="input-group">
                                
                                <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar...">

                                    <span class="input-group-btn">
                                    <button class="btn btn-default btn_buscar" type="button">Buscar!</button>
                                </span>
                                </div><!-- /input-group -->

                                </div>

                                <div class="col-sm-6">

                                   <p class="pb-0 mb-0">Categorias</p> 

                                   <select class="form-control" name="categoria" id="categoria">

                                    <option value="">Seleccione</option>

                                        @foreach($categorias as $c)

                                            <option value="{{$c->id}}">{{$c->nombre_categoria}}</option>
                                           
                                        @endforeach

                                    </select>
                                </div>


                                <div class="col-sm-6">

                                   <p class="pb-0 mb-0">Marcas</p> 

                                   <select class="form-control" name="marca" id="marca">


                                    <option value="">Seleccione</option>

                                        @foreach($marcas as $m)

                                            <option value="{{$m->id}}">{{$m->nombre_marca}}</option>
                                            
                                        @endforeach

                                    </select>
                                </div>

                                

                            </div>

                            <div class="row lista_de_productos table-responsive" style="margin-right: 1em;">
                                
                               @include('admin.pedidos.table')
                                
                            </div>
                         </div>
                         
                     </div> 

                     @else

                     <div class="alert alert-danger mt-1">Debe Seleccionar Un Cliente para ver el Catalogo de productos  </div>


                     @endif

                    
                </div><!-- End panel body  -->


            </div>


        </div>

    </div>    <!-- row-->

</section>




<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">


@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')


<div class="modal fade" id="AddDireccionModal" role="dialog" aria-labelledby="modalLabeldanger">

            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header bg-primary">

                        <h4 class="modal-title" id="modalLabeldanger">Agregar Dirección</h4>

                    </div>

                    <div class="modal-body">

                         <form action="{{ secure_url('admin/tomapedidos/postdireccion') }}" method="POST" id="dir_form" name="dir_form">

                        <div class="row">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="col-sm-6 col-sm-offset-3">

                <h4 class="text-primary">Dirección</h4>

                <div class="form-group {{ $errors->first('state_id_dir', 'has-error') }}">

                    <div class="" >

                        <select style="width:100%;" id="state_id_dir" name="state_id_dir" value="{!! old('state_id_dir') !!}" class="form-control">

                            <option value="">Seleccione Departamento</option>     

                            @foreach($states as $state)

                            <option value="{{ $state->id }}">

                                    {{ $state->state_name}}</option>

                            @endforeach

                        </select>

                    </div>

                    {!! $errors->first('state_id_dir', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="form-group {{ $errors->first('city_id_dir', 'has-error') }}">

                    <div class="" >

                        <select style="width:100%;" id="city_id_dir" name="city_id_dir" value="{!! old('city_id_dir') !!}" class="form-control">

                            <option value="">Seleccione Ciudad</option>

                        </select>

                    </div>

                    {!! $errors->first('city_id_dir', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="clearfix"></div>

                <div class="form-group">

                    <div style="padding: 0;" class="form-group  col-sm-6  col-xs-6 {{ $errors->first('id_estructura_address_dir', 'has-error') }}">

                            <select style="width:100%;" id="id_estructura_address_dir" name="id_estructura_address_dir" value="{!! old('id_estructura_address_dir') !!}" class="form-control">

                                @foreach($estructura as $estru)

                                <option value="{{ $estru->id }}">

                                {{ $estru->abrevia_estructura}} - {{ $estru->nombre_estructura}} </option>

                                @endforeach

                            </select>

                        {!! $errors->first('id_estructura_address_dir', '<span class="help-block">:message</span>') !!}

                    </div>

                    <div style="padding-right: 0;" class="form-group col-sm-6 col-xs-6  {{ $errors->first('principal_address_dir', 'has-error') }}">

                        <div class="input-group">

                            <!--span class="input-group-addon azul" id="basic-addon2">Principal</span-->

                            <input style="width:100%;" type="text" class="form-control" id="principal_address_dir" name="principal_address_dir" style="font-style:italic" value="{!! old('principal_address_dir') !!}" placeholder="Ejemplo: 100" aria-describedby="basic-addon2">

                        </div>

                        {!! $errors->first('principal_address_dir', '<span class="help-block">:message</span>') !!}

                    </div>

                </div>

                <div class="clearfix"></div>

                <div style="padding: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('secundaria_address_dir', 'has-error') }}">

                    <div class="input-group">

                        <input style="width:100%;" type="text" class="form-control" id="secundaria_address_dir" name="secundaria_address_dir" value="{!! old('secundaria_address_dir') !!}"  placeholder="Ejemplo: #21" aria-describedby="basic-addon3">

                    </div>

                    {!! $errors->first('secundaria_address_dir', '<span class="help-block">:message</span>') !!}

                </div>

                <div style="padding-right: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('edificio_address_dir', 'has-error') }}">

                    <div class="input-group">

                        <input style="width:100%;" type="text" class="form-control" value="{!! old('edificio_address_dir') !!}" id="edificio_address_dir" name="edificio_address_dir" placeholder="Ejemplo: -14" aria-describedby="basic-addon4">

                    </div>

                    {!! $errors->first('edificio_address_dir', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="clearfix"></div>

                <div class="form-group {{ $errors->first('detalle_address_dir', 'has-error') }}">

                    <input style="width:100%;" type="text" class="form-control" value="{!! old('detalle_address_dir') !!}" id="detalle_address_dir" name="detalle_address_dir" placeholder="Apto, Puerta, Interior"

                           value="{!! old('detalle_address_dir') !!}" >

                    {!! $errors->first('detalle_address_dir', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="form-group barrio_address_dir {{ $errors->first('barrio_address_dir', 'has-error') }}">

                    <input style="width:100%;" type="text" class="form-control" value="{!! old('barrio_address_dir') !!}" id="barrio_address_dir" name="barrio_address_dir" placeholder="Barrio"

                           value="{!! old('barrio_address_dir') !!}" >

                    {!! $errors->first('barrio_address_dir', '<span class="help-block">:message</span>') !!}

                </div>





                <div style="padding: 0;" class="form-group col-sm-12 id_barrio_dir {{ $errors->first('id_barrio_dir', 'has-error') }} hidden">

                    <div class="" >

                        <select style="width:100%;" id="id_barrio_dir" name="id_barrio_dir" value="{!! old('id_barrio_dir') !!}" class="form-control">

                            <option value="">Seleccione Barrio</option>

                        </select>

                    </div>

                    {!! $errors->first('id_barrio_dir', '<span class="help-block">:message</span>') !!}

                </div>





                <div class="clearfix"></div>



                    {!! $errors->first('convenio', '<span class="help-block">:message</span>') !!}

                </div-->

                <div class="clearfix"></div>

               

                <div class="clearfix"></div>

                </div>

                <div class="col-sm-6 col-sm-offset-3">

                <div class="form-group">

                    <div class="row">

                        <div class="col-sm-6">

                            <button id="btnsubmitdir" name="btnsubmitdir" type="button" class="btn btn-block btn-primary">Registrarse</button>

                        </div>

                        <div class="col-sm-6">

                            <a class="btn btn-block btn-danger" data-dismiss="modal" href="#">Cancelar</a>

                        </div>

                    </div>

                </div>





                </div>

            </div>



            </form>





                    </div>

                    

                </div>

            </div>

        </div>



























<div class="modal fade" id="AddClienteModal" role="dialog" aria-labelledby="modalLabeldanger">

            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                    <div class="modal-header bg-primary">

                        <h4 class="modal-title" id="modalLabeldanger">Agregar Cliente</h4>

                    </div>

                    <div class="modal-body">



                         <form action="{{ secure_url('admin/tomapedidos/postregistro') }}" method="POST" id="reg_form" name="reg_form">



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

                        <select style="width:100%;" id="id_type_doc" name="id_type_doc" class="form-control {{ $errors->first('id_type_doc', 'has-error') }}">

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

              





                </div>

                <div class="col-sm-6">



                <h4 class="text-primary">Dirección</h4>

                <div class="form-group {{ $errors->first('state_id', 'has-error') }}">

                    <div class="" >

                        <select style="width:100%;" id="state_id" name="state_id" value="{!! old('state_id') !!}" class="form-control">

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

                        <select style="width:100%;" id="city_id" name="city_id" value="{!! old('city_id') !!}" class="form-control">

                            <option value="">Seleccione Ciudad</option>

                        </select>

                    </div>

                    {!! $errors->first('city_id', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="clearfix"></div>

                <div class="form-group">

                    <div style="padding: 0;" class="form-group  col-sm-6  col-xs-6 {{ $errors->first('id_estructura_address', 'has-error') }}">

                            <select style="width:100%;" id="id_estructura_address" name="id_estructura_address" value="{!! old('id_estructura_address') !!}" class="form-control">

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

                            <input style="width: 100%;" type="text" class="form-control" id="principal_address" name="principal_address" style="font-style:italic" value="{!! old('principal_address') !!}" placeholder="Ejemplo: 100" aria-describedby="basic-addon2">

                        </div>

                        {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                    </div>

                </div>

                <div class="clearfix"></div>

                <div style="padding: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('secundaria_address', 'has-error') }}">

                    <div class="input-group">

                        <input style="width: 100%;" type="text" class="form-control" id="secundaria_address" name="secundaria_address" value="{!! old('secundaria_address') !!}"  placeholder="Ejemplo: #21" aria-describedby="basic-addon3">

                    </div>

                    {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                </div>

                <div style="padding-right: 0;" class="form-group col-sm-6  col-xs-6 {{ $errors->first('edificio_address', 'has-error') }}">

                    <div class="input-group">

                        <input style="width: 100%;" type="text" class="form-control" value="{!! old('edificio_address') !!}" id="edificio_address" name="edificio_address" placeholder="Ejemplo: -14" aria-describedby="basic-addon4">

                    </div>

                    {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="clearfix"></div>

                <div class="form-group {{ $errors->first('detalle_address', 'has-error') }}">

                    <input style="width: 100%;" type="text" class="form-control" value="{!! old('detalle_address') !!}" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior"

                           value="{!! old('detalle_address') !!}" >

                    {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                </div>

                <div class="form-group barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

                    <input style="width: 100%;" type="text" class="form-control" value="{!! old('barrio_address') !!}" id="barrio_address" name="barrio_address" placeholder="Barrio"

                           value="{!! old('barrio_address') !!}" >

                    {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                </div>





                <div style="padding: 0;" class="form-group col-sm-12 id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">

                    <div class="" >

                        <select style="width:100%;" id="id_barrio" name="id_barrio" value="{!! old('id_barrio') !!}" class="form-control">

                            <option value="">Seleccione Barrio</option>

                        </select>

                    </div>

                    {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}

                </div>





                <div class="clearfix"></div>



                    {!! $errors->first('convenio', '<span class="help-block">:message</span>') !!}

                </div-->

                <div class="clearfix"></div>

                <!--div class="form-group checkbox">

                    <label>

                        <input type="checkbox" name="chkalpinista" id="chkalpinista" value="1"> ¡Soy Alpinista! <small>(Opcional)</small> </a>

                    </label>

                </div-->

                <!--div class="form-group {{ $errors->first('cod_alpinista', 'has-error') }}">

                    <input type="text" class="form-control" value="{!! old('cod_alpinista') !!}" id="cod_alpinista" name="cod_alpinista" placeholder="Código de Alpinista" value="{!! old('cod_alpinista') !!}" >

                    <div class="res_cod_alpinista"></div>



                    {!! $errors->first('cod_alpinista', '<span class="help-block">:message</span>') !!}

                </div-->

                <div class="clearfix"></div>

                

                

                </div>



                <div class="col-sm-12">

                    <div class="errorregistrocliente" style="    border-radius: 5px;    padding: 0.5em;    color: #fff;"></div>



                

                <div class="form-group">

                    <div class="row">

                        <div class="col-sm-6">

                            <button id="btnsubmit" name="btnsubmit" type="button" class="btn btn-block btn-primary">Registrarse</button>

                        </div>

                        <div class="col-sm-6">

                            <a class="btn btn-block btn-danger" data-dismiss="modal" href="#">Cancelar</a>

                        </div>

                    </div>

                </div>





                </div>

            </div>



            </form>





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

                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cerrar</button>


                    </div>

                </div>

            </div>

        </div>

        

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
 <div class="modal fade" id="confirmarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Ingrese La Cantidad</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <input type="hidden"  name="idproducto_modal" id="idproducto_modal" >

                                

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Cantidad
                                    </label>
                                    <div class="col-md-8" >

                                        <input class="form-control" type="number" step="1" min="0" name="cantidad_modal" id="cantidad_modal" >
                                        
                                    </div>
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

<!-- Modal Direccion -->
  
<!-- Modal Direccion -->


<!-- Modal Direccion -->




<!-- Modal Direccion -->
 <div class="modal fade" id="avisoAlmacenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Mensaje</h4>
                    </div>
                    <div class="modal-body">
                        
                      <h3>Al Cambiar de Almacen, Se borraran los archivos que hay en el carrito.</h3>

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendConfirmar" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->
  


<div class="modal fade" id="verProductoModal" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle de Producto</h4>
            </div>
            <div class="modal-body">
            <div class="row">
            <div class="col-sm-6">
                <img id="img-producto-modal" src="" alt="">
            </div>

            <div class="col-sm-6">
                <h3 id="nombre-producto-modal"><span></span>  </h3>
                <p id="referencia-producto-modal"> <b>Referencia:</b>  <span></span></p>
                <p id="referencia-producto-sap-modal"> <b>Referencia Sap:</b>  <span></span></p>
                <p id="presentacion-producto-modal"> <b>Presentacion:</b>  <span></span></p>
                <p id="categoria-producto-modal"> <b>Categoria:</b>  <span></span></p>
                <p id="precio-producto-modal"> <b>Precio:</b>   <span></span></p>
                <p id="oferta-producto-modal"> <b> Oferta</b>: <span></span></p>
                <p id="inventario-producto-modal"> <b>Inventario:</b>  <span></span></p>
            </div>
        </div>
                
            </div>

       
    </div>
  </div>
</div>



<div class="modal fade" id="verProductoAnchetaModal" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog" style="width: 80%;  ">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Arma tu Ancheta</h4>
        </div>

            <div class="modal-body bodyancheta">


                <div class="row">
                    <div class="col-sm-6">
                        <img id="img-producto-modal" src="" alt="">
                    </div>

                    <div class="col-sm-6">
                        <h3 id="nombre-producto-modal"><span></span>  </h3>
                        <p id="referencia-producto-modal"> <b>Referencia:</b>  <span></span></p>
                        <p id="referencia-producto-sap-modal"> <b>Referencia Sap:</b>  <span></span></p>
                        <p id="presentacion-producto-modal"> <b>Presentacion:</b>  <span></span></p>
                        <p id="categoria-producto-modal"> <b>Categoria:</b>  <span></span></p>
                        <p id="precio-producto-modal"> <b>Precio:</b>   <span></span></p>
                        <p id="oferta-producto-modal"> <b> Oferta</b>: <span></span></p>
                        <p id="inventario-producto-modal"> <b>Inventario:</b>  <span></span></p>
                    </div>
                </div>

            </div>

       
    </div>
  </div>
</div>








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
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ secure_asset('assets/js/frontend/register_custom_checkout.js') }}"></script>

<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>


<script>








  document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
        if(e.keyCode == 13) {
          e.preventDefault();
        }
      }))
    });


  document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('input[type=number]').forEach( node => node.addEventListener('keypress', e => {
        if(e.keyCode == 13) {
          e.preventDefault();
        }
      }))
    });


    $(document).ready(function(){

        if($('.wrapper').hasClass('hide_menu')){

        }else{

        $('.wrapper').addClass('hide_menu');

        }

    });



  

  $("select").select2();

    $('#categoria').on('change', function(){

        $('#buscar').val('');
        $('#marca').val('').select2();

        $('.lista_de_productos').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>');

        base=$('#base').val();

        categoria=$('#categoria').val();

        $.get(base+'/admin/tomapedidos/'+categoria+'/datacategorias', function(data) {

                $('.lista_de_productos').html(data);
        });

    });


    $('#marca').on('change', function(){

        $('#buscar').val('');
        $('#categoria').val('').select2();


        $('.lista_de_productos').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>');

             base=$('#base').val();

             marca=$('#marca').val();

            $.get(base+'/admin/tomapedidos/'+marca+'/datamarcas', function(data) {

                $('.lista_de_productos').html(data);

            });
    });



    $('#almacen').on('change', function(){

             base=$('#base').val();

             almacen=$('#almacen').val();

            $.get(base+'/admin/tomapedidos/'+almacen+'/asignaalmacen', function(data) {

                    $('.lista_de_productos').html('');

                    location.reload();
            });
    });




    $('.btn_buscar').on('click', function(){

        $('.lista_de_productos').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>');

        $('#categoria').val('').select2();
        $('#marca').val('').select2();
        
        base=$('#base').val();

        buscar=$('#buscar').val();

        if(buscar==''){
            buscar='leche';
        }

        $.get(base+'/admin/tomapedidos/'+buscar+'/databuscar', function(data) {

                $('.lista_de_productos').html(data);
        });

    });


    $('.reset_buscar').on('click', function(){

        $('.lista_de_productos').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>');

        base=$('#base').val();

        $('#buscar').val('');
        $('#marca').val('').select2();
        $('#categoria').val('').select2();


        $.get(base+'/admin/tomapedidos/leche/databuscar', function(data) {

                $('.lista_de_productos').html(data);
        });

    });


    /* Inicio de js para anchetas */


    $(document).on('click','.addproductoancheta', function(){

        $('.bodyancheta').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>')

        base=$('#base').val();

        id=$(this).data('id');

        $.get(base+'/admin/tomapedidos/'+id+'/getancheta', function(data) {

            //se trae la estructura de la ancheta 

                $('.bodyancheta').html(data);


                //recarga el precio 


                $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });
                
        });

        $('#verProductoAnchetaModal').modal('show');


    });


    $(document).on('click','.finalizarAncheta', function(){

        $('.errorcantidad').html('Finalizar Ancheta');

        //alert('Finalizar ancheta ');

        id=$(this).data('id');

        cantidad=$(this).data('cantidad');
        maxima=$(this).data('maxima');

        ancheta_de=$('#ancheta_de').val();
        ancheta_para=$('#ancheta_para').val();
        ancheta_mensaje=$('#ancheta_mensaje').val();

        seleccionados=$('.tabpane'+id+" .pseleccionado").toArray().length;

        
        ban=0;

            if (cantidad <=seleccionados) {

                ban=1;

            }else{

                $('.errorcantidad').html('<div class="alert alert-danger">Desbes seleccionar al menos '+cantidad+' productos</div>');
            }

            if(ban==1){

                if(maxima>0){

                    if (maxima >=seleccionados) {

                        ban=1;

                    }else{

                        ban=0;

                        $('.errorcantidad').html('<div class="alert alert-danger">Desbes seleccionar maximo '+maxima+' productos</div>');

                    }

                }

            }

            if(ban==1){

                $.post(base+'/cart/verificarancheta', {ancheta_de, ancheta_para, ancheta_mensaje}, function(data) {
                    
                    if (data=='0') {

                        $('.addtocartunaancheta').fadeIn();

                        $('.addtocartunaancheta').focus();

                        $('.reiniciarAncheta').fadeOut();

                    }else{

                        $('.errorcantidad').html('<div class="alert alert-danger">No hay existencia disponible, de la caja de ancheta </div>');

                    }

                });
            }

        });

        $(document).ready(function(){

            $('.addtocartunaancheta').fadeOut();

            base=$('#base').val();

            $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });
            });





        $(document).on('click','.addtocartunaancheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            datasingle=$(this).data('single');

            ancheta_de=$('#ancheta_de').val();
            ancheta_para=$('#ancheta_para').val();
            ancheta_mensaje=$('#ancheta_mensaje').val();


            price=$(this).data('price')+$('.totalancheta').val();

            slug=$(this).data('slug');

            single=$('#single').val();

            url=$(this).attr('href');


        pimagen=$(this).data('pimagen');

        name=$(this).data('name');


        $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/admin/tomapedidos/agregarunaancheta', {price, slug, datasingle, ancheta_para, ancheta_de, ancheta_mensaje}, function(data) {

                $('.listaorden').html(data);

                $('#verProductoAnchetaModal').modal('hide');
            });

        });




        $(document).on('click', '.btnnetx', function(e){

        $('.addtocartunaancheta').fadeOut();

        e.preventDefault();

        href=$(this).attr('href');

        id=$(this).data('id');

        cantidad=$(this).data('cantidad');

         maxima=$(this).data('maxima');

        seleccionados=$('.tabpane'+id+" .pseleccionado").toArray().length;

        ban=0;

                if (cantidad <=seleccionados) {

                    ban=1;

                }else{

                    $('.errorcantidad').html('<div class="alert alert-danger">Desbes seleccionar al menos '+cantidad+' productos</div>');
                
                }

                if(ban==1){

                    if(maxima>0){

                        if (maxima >=seleccionados) {

                            ban=1;

                        }else{

                            ban=0;

                            $('.errorcantidad').html('<div class="alert alert-danger">Desbes seleccionar maximo '+maxima+' productos</div>');

                        }


                    }


                }




                if(ban==1){

                    $('.active').removeClass('active');

                    $(href).addClass('active');

                    $('.errorcantidad').html('');
                }

        $('.reiniciarAncheta').fadeIn();


        });




        $('.anchetabtn').on('click', function(){
        id=$(this).data('id');

        $('.anchetapanel').fadeOut('fast', function() { });
        $('.'+id).fadeIn('fast', function() { });
        });


        $(document).ready(function(){

            $('.addtocartunaancheta').fadeOut();

            base=$('#base').val();

            $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });
            });




            $(document).on('click','.addtocartancheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            price=$(this).data('price');

            slug=$(this).data('slug');

            $.post(base+'/cart/addtocartancheta', {price, slug, id}, function(data) {

                $('.p'+id+'').html(data);


                $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });


            });

            });


            $(document).on('click','.deltocartancheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            price=$(this).data('price');

            slug=$(this).data('slug');

            $.post(base+'/cart/deltocartancheta', {price, slug, id}, function(data) {

                $('.p'+id+'').html(data);

                $.get(base+'/cart/totalancheta', function(data) {

                    $('.listaancheta').html(data);

                });

            });

            });


            $(document).on('click','.reiniciarAncheta', function(e){

            e.preventDefault();

            base=$('#base').val();

            $.get(base+'/cart/reiniciarancheta', function(data) {

                    location.reload();

                });

            });






/* Fin js anccheta */



    $(document).on('click','.addproducto', function(){

            base=$('#base').val();

             id=$(this).data('id');

            $.get(base+'/admin/tomapedidos/'+id+'/addtocart', function(data) {

                    $('.listaorden').html(data);
            });
    });


    $(document).on('click','.verProducto', function(){

        nombre_producto=$(this).data('nombre_producto');
        presentacion_producto=$(this).data('presentacion_producto');
        referencia_producto=$(this).data('referencia_producto');
        referencia_producto_sap=$(this).data('referencia_producto_sap');
        nombre_categoria=$(this).data('nombre_categoria');
        precio_base=$(this).data('precio_base');
        precio_oferta=$(this).data('precio_oferta');
        inventario=$(this).data('inventario');
        imagen=$(this).data('imagen');


        $('#referencia-producto-modal span').html(referencia_producto);
        $('#referencia-producto-sap-modal span').html(referencia_producto_sap);
        $('#nombre-producto-modal span').html(nombre_producto);
        $('#presentacion-producto-modal span').html(presentacion_producto);
        $('#precio-producto-modal span').html(precio_base);
        $('#oferta-producto-modal span').html(precio_oferta);
        $('#inventario-producto-modal span').html(inventario);
        $('#categoria-producto-modal span').html(nombre_categoria);
        $('#img-producto-modal').attr('src', imagen);

        $('#verProductoModal').modal('show');

    });


        $(document).on('click','.delcar', function(){

             base=$('#base').html();

             slug=$(this).data('slug');

            $.get(base+'/admin/tomapedidos/'+slug+'/deletecart', function(data) {

                    $('.listaorden').html(data);
            });
    });

  


 $(document).on('click','.vaciarCarrito', function(){

             base=$('#base').val();

            $.get(base+'/admin/tomapedidos/vaciarcarrito', function(data) {

                    $('.listaorden').html(data);
            });
    });


    $(document).on('click','.cancelarpedido', function(){

    base=$('#base').val();

        $.get(base+'/admin/tomapedidos/cancelarpedido', function(data) {

            location.reload();
        });
    });


    $(document).on('click','.updatecart', function(){

        base=$('#base').val();

        id=$(this).data('id');

        cantidad=$(this).data('cantidad');

        tipo=$(this).data('tipo');

        if(tipo=='suma'){
            cantidad=cantidad+1;
        }

        if(tipo=='resta'){
        cantidad=cantidad-1;
        }
        
            $.get(base+'/admin/tomapedidos/'+id+'/updatecart/'+cantidad, function(data) {

                    $('.listaorden').html(data);
            });


    });


    $('.sendConfirmar').click(function(){

        base=$('#base').val();

        id=$('#idproducto_modal').val();

        cantidad=$('#cantidad_modal').val();

         $.get(base+'/admin/tomapedidos/'+id+'/updatecart/'+cantidad, function(data) {

                    $('.listaorden').html(data);

                    $('#cantidad_modal').val('');

                    $('#idproducto_modal').val('');

                    $('#confirmarOrdenModal').modal('hide');

            });

    });


    $(document).on('click','.seleccionarCliente', function(){

        $('.lista_clientes').html('');

        $('#ClienteModal').modal('show');

    });



    $(document).on('click','.asignaCliente',function(){

        var base = $('#base').val();

        id=$(this).data('id');

        $.get(base+'/admin/tomapedidos/'+id+'/asignacliente', function(data) {

                $('#ClienteModal').modal('hide');

                $('.clientecompra').html(data);

                location.reload();

        });


    });



    $('#id_address').on('change', function(){

        $('.lista_de_productos').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>');


        base=$('#base').val();

        id_address=$('#id_address').val();

        notas_orden=$('#notas_orden').val();

        _token=$('input[name="_token"]').val();

        $.post(base+'/admin/tomapedidos/notascompra', { notas_orden, _token}, function(data) {

            $.get(base+'/admin/tomapedidos/'+id_address+'/asignadireccion', function(data) {

                location.reload();

            }); 

        });

    });



    $('#id_forma_pago').on('change', function(){

        base=$('#base').val();

        id_forma_pago=$('#id_forma_pago').val();

        notas_orden=$('#notas_orden').val();

        _token=$('input[name="_token"]').val();

        $.post(base+'/admin/tomapedidos/notascompra', { notas_orden, _token}, function(data) {

            $.get(base+'/admin/tomapedidos/'+id_forma_pago+'/asignaformadepago', function(data) {

                    location.reload();

            });

        });


    });


    $('#id_forma_envio').on('change', function(){

        base=$('#base').val();

        id_forma_envio=$('#id_forma_envio').val();

        notas_orden=$('#notas_orden').val();

        _token=$('input[name="_token"]').val();

        $.post(base+'/admin/tomapedidos/notascompra', { notas_orden, _token}, function(data) {

            $.get(base+'/admin/tomapedidos/'+id_forma_envio+'/asignaformadeenvio', function(data) {

                    location.reload();

            });

        });



    });


    $(document).on('click','.addCliente', function(){

        $('#AddClienteModal').modal('show');

    });


    $(document).on('click','.agregarDireccion', function(){

        $('#AddDireccionModal').modal('show');

    }); 

    $('.btn_buscar_cliente').on('click', function(){

        $('.lista_clientes').html('<p style="text-align: center;"  ><img style="width:100px;" src="{{secure_url('assets/images/loader.gif')}}"></p>');

        base=$('#base').val();

        buscar=$('#buscar_cliente').val();

        if (buscar.length>2) {

            $.get(base+'/admin/tomapedidos/'+buscar+'/databuscarcliente', function(data) {

                    $('.lista_clientes').html(data);

            });

        }

    });


    



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







$('select[name="state_id_dir"]').on('change', function() {

var stateID = $(this).val();

var base = $('#base').val();



    if(stateID) {

        $.ajax({

            url: base+'/registro/cities/'+stateID,

            type: "GET",

            dataType: "json",

            success:function(data) {



                

                $('select[name="city_id_dir"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="city_id_dir"]').append('<option value="'+ key+'">'+ value +'</option>');

                });



            }

        });

    }else{

        $('select[name="city_id_dir"]').empty();

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




        $(document).on('click','#btnsubmit', function(e){

            codigo=0;

            $('.errorregistrocliente').html('');

            e.preventDefault();

            base=$('#base').val();

            _token=$('input[name="_token"]').val();

            email=$('#Email').val();

            if (email!='' && email!=undefined) {

                $.post(base+'/postemailregistro', { email, _token}, function(data) {

                    if (data==1) {

                        $('.res_cod_alpinista').html('');

                        var $validator = $('#reg_form').data('bootstrapValidator').validate();

                        if( $('#chkalpinista').is(':checked') ) {


                            if ($('#cod_alpinista').val()!='') {


                                if ($validator.isValid()) {

                                    $("#reg_form")[0].submit();

                                }


                            }else{


                                $('.errorregistrocliente').html('<span class="help-block">Código de Alpinista es requerido</span>');

                            }

                        }else{

                            if ($validator.isValid()) {

                               $("#reg_form")[0].submit();

                            }

                        }   

                    }else{

                        $('.errorregistrocliente').html('<span class="danger">El Email ya se encuentra registrado </span>');

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

                        $('.errorregistrocliente').html('<span class="help-block">Código de Alpinista es requerido</span>');

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






//fin select ciudad
$(document).on('click','#btnsubmitdir', function(e){

codigo=0;

e.preventDefault();

base=$('#base').val();

_token=$('input[name="_token"]').val();

var $validator = $('#dir_form').data('bootstrapValidator').validate();

if ($validator.isValid()) {

    $("#dir_form")[0].submit();
}

});



</script>
@stop
