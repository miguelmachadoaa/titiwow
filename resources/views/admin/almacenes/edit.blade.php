@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Almacen {{$almacen->nombre_almacen}}
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Almacen {{$almacen->nombre_almacen}}
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Almacens</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Almacen {{$almacen->nombre_almacen}}
                    </h4>
                </div>

               
                <div class="panel-body">
                    
                        {!! Form::model($almacen, ['url' => secure_url('admin/almacenes/'. $almacen->id), 'method' => 'PUT', 'class' => 'form-horizontal', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <input type="hidden" name="id_almacen" id="id_almacen" value="{{$almacen->id}}">
                          
                       
                        <div class="form-group {{ $errors->
                            first('nombre_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre  Almacen
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_almacen" name="nombre_almacen" class="form-control" placeholder="Nombre de Almacen"
                                       value="{!! old('nombre_almacen', $almacen->nombre_almacen) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('alias_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Alias  Almacen <small>Información que se muestra en el modal del inicio </small>
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="alias_almacen" name="alias_almacen" class="form-control" placeholder="Nombre de Almacen"
                                       value="{!! old('alias_almacen', $almacen->alias_almacen) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('alias_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                        <div class="form-group {{ $errors->
                            first('descripcion_almacen', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descripción Almacen
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_almacen" name="descripcion_almacen" placeholder="Descripción Almacen" rows="5">{!! old('descripcion_almacen', $almacen->descripcion_almacen) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <div class="form-group {{ $errors->
                            first('state_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Departamento
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="state_id" name="state_id" class="form-control select2">

                                    <option value="">Seleccione</option>
                                    <option value="0" @if($almacen->id_state=='0') {{'Selected'}} @endif >Todos</option>
                                    
                                    @foreach($states as $state)

                                    <option @if($almacen->id_state==$state->id) {{'Selected'}} @endif  value="{{ $state->id }}">

                                            {{ $state->state_name}}</option>

                                    @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('state_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('city_id', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Ciudad
                            </label>
                            <div class="col-sm-5">
                                
                                 <select  id="city_id" name="city_id" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>

                                    <option value="0" @if($almacen->id_city=='0') {{'Selected'}} @endif >Todos</option>



                    
                                    @foreach($cities as $c)

                                        <option @if($almacen->id_city==$c->id) {{'Selected'}} @endif  value="{{ $c->id }}"> {{ $c->city_name}}</option>

                                    @endforeach
                                  
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('city_id', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>







                        <div class="form-group col-sm-12  clearfix">

                            <label for="title" class="col-sm-3 col-xs-12 control-label">Imagen de Ubicacion de almacen</label>

                            <div class="col-sm-9 col-xs-12">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    @if(!is_null($almacen->imagen_almacen))

                                        <img src="{{URL::to('uploads/almacenes/'.$almacen->imagen_almacen)}}" class="img-responsive" alt="Image">

                                    @else
                                        
                                        <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."
                                             class="img-responsive"/>

                                    @endif

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px;">
                                         
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-file">

                                        <span class="fileinput-new">Seleccione Imagen </span>

                                        <span class="fileinput-exists">Cambiar</span>

                                        <input type="file" name="imagen_almacen" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Eliminar</span>

                                </div>

                            </div>
                            </div>

                        </div>







                          <div class="form-group {{ $errors->
                            first('minimo_compra', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Minimo de Compra para almacen
                            </label>
                            <div class="col-sm-5">
                                
                               <input type="number" step="0.01" min="0"  id="minimo_compra" name="minimo_compra" class="form-control" placeholder="Minimo Compra"
                                            value="{!! old('minimo_compra', $almacen->minimo_compra) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('minimo_compra', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('hora', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Hora para envio de reporte
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="hora" name="hora" type="text" placeholder="Hora de envio reporte" value="{!! old('hora', $almacen->hora) !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('hora', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        
                        <div class="form-group {{ $errors->
                            first('correos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Correos de Envio de Reporte <small>Separar con ;</small>
                            </label>
                            <div class="col-sm-5">
                                
                               <input style="margin: 4px 0;" id="correos" name="correos" type="text" placeholder="Correos de Envio" value="{!! old('correos', $almacen->correos) !!}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('correos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->
                            first('descuento_productos', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Mostrar descuento en productos
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="descuento_productos" name="descuento_productos" class="form-control select2">
                                    <option @if($almacen->descuento_productos=='0') {{'Selected'}} @endif value="0">No Mostrar </option>
                                    <option @if($almacen->descuento_productos=='1') {{'Selected'}} @endif value="1">Mostrar</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descuento_productos', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('formato', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Envio de Formato de Solicitud
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="formato" name="formato" class="form-control select2">
                                    <option @if($almacen->formato=='0') {{'Selected'}} @endif value="0">No Enviar  </option>
                                    <option @if($almacen->formato=='1') {{'Selected'}} @endif value="1">Enviar</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('formato', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                        <div class="form-group {{ $errors->
                            first('defecto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                               Tipo de Almacen
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="tipo_almacen" name="tipo_almacen" class="form-control select2">
                                    <option @if($almacen->tipo_almacen=='0') {{'Selected'}} @endif value="0">Normal</option>
                                    <option @if($almacen->tipo_almacen=='1') {{'Selected'}} @endif value="1">Nomina</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('tipo_almacen', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>




                         <div class="form-group {{ $errors->
                            first('defecto', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Almacen por Defecto 
                            </label>
                            <div class="col-sm-5">
                                
                                <select id="defecto" name="defecto" class="form-control select2">
                                    <option @if($almacen->defecto=='0') {{'Selected'}} @endif value="0">No</option>
                                    <option @if($almacen->defecto=='1') {{'Selected'}} @endif value="1">Si</option>
                                    
                                                      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('defecto', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                         <div class="form-group {{ $errors->first('mensaje_promocion', 'has-error') }}">
                                    <label for="mensaje_promocion" class="col-sm-2 control-label">
                                        Mensaje de promociones
                                    </label>
                                    <div class="col-sm-5">
                                        
                                        <textarea id="mensaje_promocion" name="mensaje_promocion" cols="30" rows="10" class="form-control" placeholder="Mensaje de Promociones">{!! old('mensaje_promocion', $almacen->mensaje_promocion) !!}</textarea>
                                       
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('mensaje_promocion', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                <div class="form-group {{ $errors->first('codigo_almacen', 'has-error') }}">
                    <label for="codigo_almacen" class="col-sm-2 control-label">
                        Codigo Almacen
                    </label>
                    <div class="col-sm-5">
                        
                        <input id="codigo_almacen" name="codigo_almacen" class="form-control" placeholder="Codigo Almacen" value="{!! old('codigo_almacen', $almacen->codigo_almacen) !!}"></input>
                    </div>
                    <div class="col-sm-4">
                        {!! $errors->first('codigo_almacen', '<span class="help-block">:message</span> ') !!}
                    </div>
                </div>



                                
                        <fieldset>
                            <h3>Datos para pagos </h3>


                            <hr />
                            <div class="form-group {{ $errors->first('id_mercadopago', 'has-error') }}">
                                <label for="id_mercadopago" class="col-sm-2 control-label">
                                    ID Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="id_mercadopago" name="id_mercadopago" class="form-control" placeholder="ID Mercadopago"
                                        value="{!! old('id_mercadopago', $almacen->id_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('id_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('key_mercadopago', 'has-error') }}">
                                <label for="key_mercadopago" class="col-sm-2 control-label">
                                    Key Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="key_mercadopago" name="key_mercadopago" class="form-control" placeholder="Key Mercadopago"
                                        value="{!! old('key_mercadopago', $almacen->key_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('key_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('public_key_mercadopago', 'has-error') }}">
                                <label for="public_key_mercadopago" class="col-sm-2 control-label">
                                    Public Key Mercadopago Produccion
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="public_key_mercadopago" name="public_key_mercadopago" class="form-control" placeholder="Public Key Mercadopago Produccion"
                                        value="{!! old('public_key_mercadopago', $almacen->public_key_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('public_key_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('public_key_mercadopago_test', 'has-error') }}">
                                <label for="public_key_mercadopago_test" class="col-sm-2 control-label">
                                    Public Key Mercadopago Test
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="public_key_mercadopago_test" name="public_key_mercadopago_test" class="form-control" placeholder="Public Key Mercadopago Test"
                                        value="{!! old('public_key_mercadopago_test', $almacen->public_key_mercadopago_test) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('public_key_mercadopago_test', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('comision_mp_baloto', 'has-error') }}">
                                <label for="comision_mp_baloto" class="col-sm-2 control-label">
                                    % Comision Mercado Pago Baloto
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="comision_mp_baloto" name="comision_mp_baloto" class="form-control" placeholder="% Comision Mercado Pago Baloto"
                                        value="{!! old('comision_mp_baloto', $almacen->comision_mp_baloto) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('comision_mp_baloto', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('comision_mp_efecty', 'has-error') }}">
                                <label for="comision_mp_efecty" class="col-sm-2 control-label">
                                    % Comision Mercado Pago Efecty
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="comision_mp_efecty" name="comision_mp_efecty" class="form-control" placeholder="% Comision Mercado Pago Efecty"
                                        value="{!! old('comision_mp_efecty', $almacen->comision_mp_efecty) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('comision_mp_efecty', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('comision_mp_pse', 'has-error') }}">
                                <label for="comision_mp_pse" class="col-sm-2 control-label">
                                    % Comision Mercado Pago PSE
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="comision_mp_pse" name="comision_mp_pse" class="form-control" placeholder="% Comision Mercado Pago PSE"
                                        value="{!! old('comision_mp_pse', $almacen->comision_mp_pse) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('comision_mp_pse', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('comision_mp', 'has-error') }}">
                                <label for="comision_mp" class="col-sm-2 control-label">
                                    % Comision Mercado Pago Credit Card
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="comision_mp" name="comision_mp" class="form-control" placeholder="% Comision Mercado Pago Credit Card"
                                        value="{!! old('comision_mp', $almacen->comision_mp) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('comision_mp', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('retencion_fuente_mp', 'has-error') }}">
                                <label for="retencion_fuente_mp" class="col-sm-2 control-label">
                                    % Retencion de Fuente Mercado Pago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="retencion_fuente_mp" name="retencion_fuente_mp" class="form-control" placeholder="% Retencion de Fuente Mercado Pago"
                                        value="{!! old('retencion_fuente_mp', $almacen->retencion_fuente_mp) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('retencion_fuente_mp', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('retencion_iva_mp', 'has-error') }}">
                                <label for="retencion_iva_mp" class="col-sm-2 control-label">
                                    % Retencion de IVA Mercado Pago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="retencion_iva_mp" name="retencion_iva_mp" class="form-control" placeholder="% Retencion de IVA Mercado Pago"
                                        value="{!! old('retencion_iva_mp', $almacen->retencion_iva_mp) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('retencion_iva_mp', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('retencion_ica_mp', 'has-error') }}">
                                <label for="retencion_ica_mp" class="col-sm-2 control-label">
                                    % Retencion de ICA Mercado Pago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="retencion_ica_mp" name="retencion_ica_mp" class="form-control" placeholder="% Retencion de ICA Mercado Pago"
                                        value="{!! old('retencion_ica_mp', $almacen->retencion_ica_mp) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('retencion_ica_mp', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                           <!-- <div class="form-group {{ $errors->first('key_mercadopago', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Key Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="key_mercadopago" name="key_mercadopago" class="form-control" placeholder="Key Mercadopago"
                                        value="{!! old('key_mercadopago', $almacen->key_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('key_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>-->

                            <div class="form-group  {{ $errors->first('mercadopago_sand', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Modo Mercadopago
                                </label>
                                <div class="col-sm-5">   
                                 <select id="mercadopago_sand" name="mercadopago_sand" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 1 }}"

                                                @if($almacen->mercadopago_sand == 1) selected="selected" @endif 

                                                >Modo Sandbox</option>

                                        <option value="{{ 2 }}"
                                                @if($almacen->mercadopago_sand == 2) selected="selected" @endif >Modo Real</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('mercadopago_sand', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>

                        </fieldset>


                         <fieldset>
                            <h3>Datos para Epayco </h3>

                            <hr />

                            <div class="form-group {{ $errors->first('epayco_id_cliente', 'has-error') }}">
                                <label for="epayco_id_cliente" class="col-sm-2 control-label">
                                    ID Epayco
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="epayco_id_cliente" name="epayco_id_cliente" class="form-control" placeholder="ID Epayco"
                                        value="{!! old('epayco_id_cliente', $almacen->epayco_id_cliente) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('epayco_id_cliente', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('epayco_key', 'has-error') }}">
                                <label for="epayco_key" class="col-sm-2 control-label">
                                    Key Epayco
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="epayco_key" name="epayco_key" class="form-control" placeholder="Key Epayco"
                                        value="{!! old('epayco_key', $almacen->epayco_key) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('epayco_key', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('epayco_public_key', 'has-error') }}">
                                <label for="epayco_public_key" class="col-sm-2 control-label">
                                    Public Key Epayco
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="epayco_public_key" name="epayco_public_key" class="form-control" placeholder="Public Key Epayco"
                                        value="{!! old('epayco_public_key', $almacen->epayco_public_key) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('epayco_public_key', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->first('epayco_private_key', 'has-error') }}">
                                <label for="epayco_private_key" class="col-sm-2 control-label">
                                    Private Key Epayco
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="epayco_private_key" name="epayco_private_key" class="form-control" placeholder="Private Key Epaycon"
                                        value="{!! old('epayco_private_key', $almacen->epayco_private_key) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('epayco_private_key', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                           

                           
                         

                            <div class="form-group  {{ $errors->first('epayco_sand', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Modo Epayco
                                </label>
                                <div class="col-sm-5">   
                                 <select id="epayco_sand" name="epayco_sand" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 1 }}"
                                                @if($almacen->epayco_sand == 1) selected="selected" @endif >Modo Sandbox</option>

                                        <option value="{{ 2 }}"
                                                @if($almacen->epayco_sand == 2) selected="selected" @endif >Modo Real</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('epayco_sand', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>



                        </fieldset>





                        <fieldset>
                            <h3>Dirección del Almacen</h3>


                            @if(isset($direccion->id))

                            <input type="hidden" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="Direccion de Almacen" >

                     
                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >
                                
                                <select id="id_estructura_address" name="id_estructura_address" class="form-control">

                                    @foreach($estructura as $estru)

                                    <option @if($direccion->id_estructura_address==$estru->id)  {{'Selected'}} @endif value="{{ $estru->id }}">{{ $estru->nombre_estructura}} </option>

                                    @endforeach

                                </select>

                                 {!! $errors->first('id_estructura_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">
                                
                                <input type="text" id="principal_address" name="principal_address" class="form-control" placeholder="Ejemplo: 44 " value="{!! old('principal_address', $direccion->principal_address) !!}" >

                                {!! $errors->first('principal_address', '<span class="help-block">:message</span>') !!}

                            </div>
                                        
                        </div>


                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >

                                <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address',  $direccion->secundaria_address) !!}" >

                                {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">

                                <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address',  $direccion->edificio_address) !!}" >

                                {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                            </div>

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('detalle_address', 'has-error') }}">

                            <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address',   $direccion->detalle_address) !!}" >

                            {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

                            <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address',   $direccion->barrio_address) !!}" >

                            {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                         <div style="margin-left: 8%;" class="form-group col-sm-7  id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
                            <div class="" >
                                <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio',   $direccion->id_barrio) !!}" class="form-control">
                                    <option value="">Seleccione Barrio</option>
                                </select>
                            </div>
                            {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('notas', 'has-error') }}">

                            <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" >{{$direccion->notas}}</textarea>

                            {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                        </div>

                        @else

                             <input type="hidden" class="form-control" id="titulo" name="titulo" placeholder="Nombre para esta dirección" value="Direccion de Almacen" >

                     
                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

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


                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('calle_address', 'has-error') }}">

                            <div class="col-sm-6" >

                                <input type="text" id="secundaria_address" name="secundaria_address" placeholder="Ejemplo: #14 " class="form-control" value="{!! old('secundaria_address') !!}" >

                                {!! $errors->first('secundaria_address', '<span class="help-block">:message</span>') !!}

                            </div>

                            <div class="col-sm-6">

                                <input type="text" id="edificio_address" name="edificio_address" class="form-control" placeholder="Ejemplo: 100 " value="{!! old('edificio_address') !!}" >

                                {!! $errors->first('edificio_address', '<span class="help-block">:message</span>') !!}

                            </div>

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('detalle_address', 'has-error') }}">

                            <input type="text" class="form-control" id="detalle_address" name="detalle_address" placeholder="Apto, Puerta, Interior" value="{!! old('detalle_address') !!}" >

                            {!! $errors->first('detalle_address', '<span class="help-block">:message</span>') !!}

                        </div>

                        <div style="  margin-bottom: 1em;" class=" col-sm-7  barrio_address {{ $errors->first('barrio_address', 'has-error') }}">

                            <input type="text" class="form-control" id="barrio_address" name="barrio_address" placeholder="Barrio" value="{!! old('barrio_address') !!}" >

                            {!! $errors->first('barrio_address', '<span class="help-block">:message</span>') !!}

                        </div>

                         <div style="margin-left: 8%;" class="form-group col-sm-7  id_barrio {{ $errors->first('id_barrio', 'has-error') }} hidden">
                            <div class="" >
                                <select id="id_barrio" name="id_barrio" value="{!! old('id_barrio') !!}" class="form-control">
                                    <option value="">Seleccione Barrio</option>
                                </select>
                            </div>
                            {!! $errors->first('id_barrio', '<span class="help-block">:message</span>') !!}
                        </div>



                        <div style="  margin-bottom: 1em;" class=" col-sm-7  {{ $errors->first('notas', 'has-error') }}">

                            <textarea style="margin: 4px 0;" id="notas" name="notas" type="text" placeholder="Notas" class="form-control" ></textarea>

                            {!! $errors->first('notas', '<span class="help-block">:message</span>') !!}

                        </div>



                        @endif



                        </fieldset>






                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.almacenes.index') }}">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">



    <!-- row-->
</section>

@stop
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
 <script src="{{ secure_asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ secure_asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>




<script>
    

      $('select[name="state_id"]').on('change', function() {
        var stateID = $(this).val();
        var base = $('#base').val();

        if(stateID) {
            $.ajax({
                url: base+'/configuracion/citiestodos/'+stateID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                    
                    $('select[name="city_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="city_id"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                    });

                }
            });
        }else{
            $('select[name="city_id"]').empty();
        }
    });


         $("#hora").datetimepicker({
            format: 'HH:mm'
        }).parent().css("position :relative");



    $('body').on('click', '.delciudad', function(){

            $('#del_id').val($(this).data('id'));

            $("#delCiudadModal").modal('show');

        });




</script>


@stop