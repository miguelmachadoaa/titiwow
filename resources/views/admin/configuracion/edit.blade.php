@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Configuración General
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Configuración General
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Configuración</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content contain_body">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Configuración
                    </h4>
                </div>
                <div class="panel-body">

         <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                    
                    
                        {!! Form::model($configuracion, ['url' => secure_url('admin/configuracion/'. $configuracion->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                            <div class="form-group {{ $errors->first('nombre_tienda', 'has-error') }}">
                                <label for="nombre_tienda" class="col-sm-2 control-label">
                                    Nombre Tienda
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="nombre_tienda" name="nombre_tienda" class="form-control" placeholder="Nombre Tienda"
                                        value="{!! old('nombre_tienda', $configuracion->nombre_tienda) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('nombre_tienda', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('base_url', 'has-error') }}">
                                <label for="base_url" class="col-sm-2 control-label">
                                    Base Url
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="base_url" name="base_url" class="form-control" placeholder="Nombre Tienda"
                                        value="{!! old('base_url', $configuracion->base_url) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('base_url', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                            <div class="form-group {{ $errors->first('limite_amigos', 'has-error') }}">
                                <label for="limite_amigos" class="col-sm-2 control-label">
                                    Límite de Amigos Alpina
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="limite_amigos" name="limite_amigos" class="form-control" placeholder="Límite de Amigos Alpina"
                                        value="{!! old('limite_amigos', $configuracion->limite_amigos) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('limite_amigos', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>


                             <div class="form-group {{ $errors->first('token_api', 'has-error') }}">
                                    <label for="token_api" class="col-sm-2 control-label">
                                        Token Api
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="token_api" name="token_api" class="form-control" placeholder="Token api "
                                            value="{!! old('token_api', $configuracion->token_api) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('token_api', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                          

                            

                            <div class="form-group {{ $errors->first('maximo_productos', 'has-error') }}">
                                <label for="maximo_productos" class="col-sm-2 control-label">
                                    Máximo Productos
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" min="0"  id="maximo_productos" name="maximo_productos" class="form-control" placeholder="Máximo Productos"
                                        value="{!! old('maximo_productos', $configuracion->maximo_productos) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('maximo_productos', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->first('vence_ordenes', 'has-error') }}">
                                <label for="vence_ordenes" class="col-sm-2 control-label">
                                    Tiempo para vencer ordenes
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" min="0"  id="vence_ordenes" name="vence_ordenes" class="form-control" placeholder="Tiempo para vencer ordenes"
                                        value="{!! old('vence_ordenes', $configuracion->vence_ordenes) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('vence_ordenes', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>



                            <div class="form-group {{ $errors->first('vence_ordenes_pago', 'has-error') }}">
                                <label for="vence_ordenes_pago" class="col-sm-2 control-label">
                                    Tiempo para vencer ordenes por pago <small>Tiempo mínimo para cancelar la orden en compramas</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" step="1" min="0"  id="vence_ordenes_pago" name="vence_ordenes_pago" class="form-control" placeholder="Tiempo para vencer ordenes por pago"
                                        value="{!! old('vence_ordenes_pago', $configuracion->vence_ordenes_pago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('vence_ordenes_pago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('dias_abono', 'has-error') }}">
                                <label for="dias_abono" class="col-sm-2 control-label">
                                    Días adicionales por defecto al crear Códigos de Bono
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" min="0"  id="dias_abono" name="dias_abono" class="form-control" placeholder="Dias adicionales por defectoa al crear Codigos de Bono"
                                        value="{!! old('dias_abono', $configuracion->dias_abono) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('dias_abono', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                            <div class="form-group  {{ $errors->first('popup', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Popup
                                </label>
                                <div class="col-sm-5">   
                                 <select id="popup" name="popup" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 0 }}"
                                                @if($configuracion->popup == 0) selected="selected" @endif >Desactivado</option>

                                        <option value="{{ 1 }}"
                                                @if($configuracion->popup == 1) selected="selected" @endif >Activado</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('popup', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>
                            <div class="form-group {{ $errors->first('popup_titulo', 'has-error') }}">
                                    <label for="popup_titulo" class="col-sm-2 control-label">
                                        Titulo para el Popup
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="popup_titulo" name="popup_titulo" class="form-control" placeholder="Titulo para el Popup"
                                            value="{!! old('popup_titulo', $configuracion->popup_titulo) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('popup_titulo', '<span class="help-block">:message</span> ') !!}
                                    </div>
                            </div>

                            
                            <div class="form-group {{ $errors->first('popup_mensaje', 'has-error') }}">
                                    <label for="popup_mensaje" class="col-sm-2 control-label">
                                        Mensaje de popup
                                    </label>
                                    <div class="col-sm-5">
                                        
                                        <textarea id="popup_mensaje" name="popup_mensaje"  cols="30" rows="10" class="form-control" placeholder="Mensaje de Popoup">{!! old('popup_mensaje', $configuracion->popup_mensaje) !!}</textarea>
                                       
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('popup_mensaje', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <hr />

                             <div class="form-group  {{ $errors->first('explicacion_precios', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Explicación de Precios
                                </label>
                                <div class="col-sm-5">   
                                 <select id="explicacion_precios" name="explicacion_precios" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 1 }}"
                                                @if($configuracion->explicacion_precios == 1) selected="selected" @endif >Mostrar</option>

                                        <option value="{{ 0}}"
                                                @if($configuracion->explicacion_precios == 0) selected="selected" @endif >No Mostrar</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('explicacion_precios', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>


                            <hr />
                            <!--div class="form-group {{ $errors->first('id_mercadopago', 'has-error') }}">
                                <label for="id_mercadopago" class="col-sm-2 control-label">
                                    ID Mercadopago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="id_mercadopago" name="id_mercadopago" class="form-control" placeholder="ID Mercadopago"
                                        value="{!! old('id_mercadopago', $configuracion->id_mercadopago) !!}">
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
                                        value="{!! old('key_mercadopago', $configuracion->key_mercadopago) !!}">
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
                                    <input type="text" id="public_key_mercadopago" name="public_key_mercadopago" class="form-control" placeholder="Key Mercadopago"
                                        value="{!! old('public_key_mercadopago', $configuracion->public_key_mercadopago) !!}">
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
                                    <input type="text" id="public_key_mercadopago_test" name="public_key_mercadopago_test" class="form-control" placeholder="Key Mercadopago"
                                        value="{!! old('public_key_mercadopago_test', $configuracion->public_key_mercadopago_test) !!}">
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
                                        value="{!! old('comision_mp_baloto', $configuracion->comision_mp_baloto) !!}">
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
                                        value="{!! old('comision_mp_efecty', $configuracion->comision_mp_efecty) !!}">
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
                                        value="{!! old('comision_mp_pse', $configuracion->comision_mp_pse) !!}">
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
                                        value="{!! old('comision_mp', $configuracion->comision_mp) !!}">
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
                                        value="{!! old('retencion_fuente_mp', $configuracion->retencion_fuente_mp) !!}">
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
                                        value="{!! old('retencion_iva_mp', $configuracion->retencion_iva_mp) !!}">
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
                                        value="{!! old('retencion_ica_mp', $configuracion->retencion_ica_mp) !!}">
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
                                        value="{!! old('key_mercadopago', $configuracion->key_mercadopago) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('key_mercadopago', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>-->

                            <!--div class="form-group  {{ $errors->first('mercadopago_sand', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Modo Mercadopago
                                </label>
                                <div class="col-sm-5">   
                                 <select id="mercadopago_sand" name="mercadopago_sand" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 1 }}"
                                                @if($configuracion->mercadopago_sand == 1) selected="selected" @endif >Modo Sandbox</option>

                                        <option value="{{ 2 }}"
                                                @if($configuracion->mercadopago_sand == 2) selected="selected" @endif >Modo Real</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('mercadopago_sand', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div-->

                            <div class="form-group  {{ $errors->first('registro_publico', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Registro Publico
                                </label>
                                <div class="col-sm-5">   
                                 <select id="registro_publico" name="registro_publico" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 1 }}"
                                                @if($configuracion->registro_publico == 1) selected="selected" @endif >Habilitado</option>

                                        <option value="{{ 0}}"
                                                @if($configuracion->registro_publico == 0) selected="selected" @endif >Deshabilitado</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('registro_publico', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>


                             <div class="form-group  {{ $errors->first('editar_direccion', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Editar Dirección
                                </label>
                                <div class="col-sm-5">   
                                 <select id="editar_direccion" name="editar_direccion" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 0 }}"
                                                @if($configuracion->editar_direccion == 0) selected="selected" @endif >Habilitado</option>

                                        <option value="{{ 1}}"
                                                @if($configuracion->editar_direccion == 1) selected="selected" @endif >Deshabilitado</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('editar_direccion', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>


                             <div class="form-group  {{ $errors->first('user_activacion', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Activación de Usuario
                                </label>
                                <div class="col-sm-5">   
                                 <select id="user_activacion" name="user_activacion" class="form-control ">
                                    <option value="">Seleccione</option>
                                        
                                       
                                        <option value="{{ 0 }}"
                                                @if($configuracion->user_activacion == 0) selected="selected" @endif >Automatico</option>

                                        <option value="{{ 1}}"
                                                @if($configuracion->user_activacion == 1) selected="selected" @endif >Manual</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('user_activacion', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>


                             <div class="form-group {{ $errors->first('cuenta_twitter', 'has-error') }}">
                                <label for="cuenta_twitter" class="col-sm-2 control-label">
                                    Cuenta Twitter <small>(Colocar usuario sin el @)</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="cuenta_twitter" name="cuenta_twitter" class="form-control" placeholder="Cuenta Twitter"
                                        value="{!! old('cuenta_twitter', $configuracion->cuenta_twitter) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('cuenta_twitter', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>


                            


                            <fieldset>


                                <br />
                                <h4>Api Compramas</h4>
                                <hr>

                                <div class="form-group {{ $errors->first('compramas_hash', 'has-error') }}">
                                    <label for="compramas_hash" class="col-sm-2 control-label">
                                        Compramas Hash
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="compramas_hash" name="compramas_hash" class="form-control" placeholder="Compramas Hash"
                                            value="{!! old('compramas_hash', $configuracion->compramas_hash) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('compramas_hash', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('compramas_token', 'has-error') }}">
                                    <label for="compramas_token" class="col-sm-2 control-label">
                                        Compramas Token 
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="compramas_token" name="compramas_token" class="form-control" placeholder="Compramas Token "
                                            value="{!! old('compramas_token', $configuracion->compramas_token) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('compramas_token', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('compramas_url', 'has-error') }}">
                                    <label for="compramas_url" class="col-sm-2 control-label">
                                        Compramas Url 
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="compramas_url" name="compramas_url" class="form-control" placeholder="Compramas Url "
                                            value="{!! old('compramas_url', $configuracion->compramas_url) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('compramas_url', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                            </fieldset>




                             <fieldset>


                                <br />
                                <h4>Api ICG</h4>
                                <hr>

                                <div class="form-group {{ $errors->first('username_icg', 'has-error') }}">
                                    <label for="username_icg" class="col-sm-2 control-label">
                                        Username ICG
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="username_icg" name="username_icg" class="form-control" placeholder="Username ICG"
                                            value="{!! old('username_icg', $configuracion->username_icg) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('username_icg', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('password_icg', 'has-error') }}">
                                    <label for="password_icg" class="col-sm-2 control-label">
                                        Password ICG
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="password_icg" name="password_icg" class="form-control" placeholder="Password ICG"
                                            value="{!! old('password_icg', $configuracion->password_icg) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('password_icg', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('endpoint_icg', 'has-error') }}">
                                    <label for="endpoint_icg" class="col-sm-2 control-label">
                                        Endpoint ICG 
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="endpoint_icg" name="endpoint_icg" class="form-control" placeholder="Endpoint ICG"
                                            value="{!! old('endpoint_icg', $configuracion->endpoint_icg) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('endpoint_icg', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('token_icg', 'has-error') }}">
                                    <label for="token_icg" class="col-sm-2 control-label">
                                        Token ICG 
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="token_icg" name="token_icg" class="form-control" placeholder="Token ICG"
                                            value="{!! old('token_icg', $configuracion->token_icg) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('token_icg', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>



                                <div class="form-group {{ $errors->first('porcentaje_icg', 'has-error') }}">
                                    <label for="porcentaje_icg" class="col-sm-2 control-label">
                                        Porcentaje de Descuento ICG 
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="porcentaje_icg" name="porcentaje_icg" class="form-control" placeholder="Porcentaje de Descuento ICG "
                                            value="{!! old('porcentaje_icg', $configuracion->porcentaje_icg) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('porcentaje_icg', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>




                            </fieldset>







                            <br />
                                <h4>Seo Principal</h4>
                                <hr>

                                <div class="form-group {{ $errors->first('seo_title', 'has-error') }}">
                                    <label for="seo_title" class="col-sm-2 control-label">
                                        SEO Title
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="seo_title" name="seo_title" class="form-control" placeholder="SEO Title"
                                            value="{!! old('seo_title', $configuracion->seo_title) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_title', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('seo_type', 'has-error') }}">
                                    <label for="seo_type" class="col-sm-2 control-label">
                                        SEO Type
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="seo_type" name="seo_type" class="form-control" placeholder="SEO Type"
                                            value="{!! old('seo_type', $configuracion->seo_type) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_type', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('seo_url', 'has-error') }}">
                                    <label for="seo_url" class="col-sm-2 control-label">
                                        SEO URL
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="seo_url" name="seo_url" class="form-control" placeholder="SEO URL"
                                            value="{!! old('seo_url', $configuracion->seo_url) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_url', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('seo_image', 'has-error') }}">
                                    <label for="seo_image" class="col-sm-2 control-label">
                                        SEO Image
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="seo_image" name="seo_image" class="form-control" placeholder="SEO Image"
                                            value="{!! old('seo_image', $configuracion->seo_image) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_image', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('seo_site_name', 'has-error') }}">
                                    <label for="seo_site_name" class="col-sm-2 control-label">
                                        SEO Title
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="seo_site_name" name="seo_site_name" class="form-control" placeholder="SEO Site Name"
                                            value="{!! old('seo_site_name', $configuracion->seo_site_name) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_site_name', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('seo_description', 'has-error') }}">
                                    <label for="seo_description" class="col-sm-2 control-label">
                                        SEO Description
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="seo_description" name="seo_description" class="form-control" maxlength="160" placeholder="SEO Description"
                                            value="{!! old('seo_description', $configuracion->seo_description) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_description', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('h1_home', 'has-error') }}">
                                    <label for="h1_home" class="col-sm-2 control-label">
                                        H1 Home
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="h1_home" name="h1_home" class="form-control" placeholder="H1 Home"
                                            value="{!! old('h1_home', $configuracion->h1_home) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('h1_home', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('h1_marcas', 'has-error') }}">
                                    <label for="h1_marcas" class="col-sm-2 control-label">
                                        H1 Marcas
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="h1_marcas" name="h1_marcas" class="form-control" placeholder="H1 Marcas"
                                            value="{!! old('h1_marcas', $configuracion->h1_marcas) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('h1_marcas', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('h1_categorias', 'has-error') }}">
                                    <label for="h1_categorias" class="col-sm-2 control-label">
                                        H1 Categorías
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="h1_categorias" name="h1_categorias" class="form-control" placeholder="H1 Categorias"
                                            value="{!! old('h1_categorias', $configuracion->h1_categorias) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('h1_categorias', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('h1_terminos', 'has-error') }}">
                                    <label for="h1_terminos" class="col-sm-2 control-label">
                                        H1 Términos
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="h1_terminos" name="h1_terminos" class="form-control" placeholder="H1 Terminos"
                                            value="{!! old('h1_terminos', $configuracion->h1_terminos) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('h1_terminos', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                 <fieldset >

                                     <div class="col-sm-10 col-sm-offset-2">
                            
                                                <h3>Opciones robots.</h3>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_all" name="robots_all" value="all"    @if(in_array('all', $robots)) {{'checked'}} @endif >
                                                       All
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_index" name="robots_index" value="index"    @if(in_array('index', $robots)) {{'checked'}} @endif >
                                                       Index
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_follow" name="robots_follow" value="follow"    @if(in_array('follow', $robots)) {{'checked'}} @endif >
                                                       Follow
                                                      </label>
                                                    </div>



                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_noindex" name="robots_noindex" value="noindex"  @if(in_array('noindex', $robots)) {{'checked'}} @endif >
                                                       noindex
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_nofollow" name="robots_nofollow" value="nofollow" @if(in_array('nofollow', $robots)) {{'checked'}} @endif >
                                                       nofollow
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_none" name="robots_none" value="none" @if(in_array('none', $robots)) {{'checked'}} @endif >
                                                       none
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_noarchive" name="robots_noarchive" value="noarchive" @if(in_array('noarchive', $robots)) {{'checked'}} @endif >
                                                       noarchive
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_nosnippet" name="robots_nosnippet" value="nosnippet" @if(in_array('nosnippet', $robots)) {{'checked'}} @endif >
                                                       nosnippet
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_notranslate" name="robots_notranslate" value="notranslate" @if(in_array('notranslate', $robots)) {{'checked'}} @endif >
                                                       notranslate
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_noimageindex" name="robots_noimageindex" value="noimageindex" @if(in_array('noimageindex', $robots)) {{'checked'}} @endif >
                                                       noimageindex
                                                      </label>
                                                    </div>
                                                    </div>

                                                </fieldset>

                                          

                            <br>
                            <br>


                            <fieldset>
                                
                                <legend>Correos para notificacion</legend>



                                <div class="form-group {{ $errors->first('mensaje_promocion', 'has-error') }}">
                                    <label for="mensaje_promocion" class="col-sm-2 control-label">
                                        Mensaje de promociones
                                    </label>
                                    <div class="col-sm-5">
                                        
                                        <textarea id="mensaje_promocion" name="mensaje_promocion" cols="30" rows="10" class="form-control" placeholder="Mensaje de Promociones">{!! old('mensaje_promocion', $configuracion->mensaje_promocion) !!}</textarea>
                                       
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('mensaje_promocion', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                  <div class="form-group {{ $errors->first('mensaje_bienvenida', 'has-error') }}">
                                    <label for="mensaje_bienvenida" class="col-sm-2 control-label">
                                        Mensaje de Bienvenida Nuevos Usuarios
                                    </label>
                                    <div class="col-sm-5">
                                        
                                        <textarea id="mensaje_bienvenida" name="mensaje_bienvenida" cols="30" rows="10" class="form-control" placeholder="Mensaje de Bienvenida Nuevos Usuarios">{!! old('mensaje_bienvenida', $configuracion->mensaje_bienvenida) !!}</textarea>
                                       
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('mensaje_bienvenida', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>




                                <div class="form-group {{ $errors->first('correo_admin', 'has-error') }}">
                                    <label for="correo_admin" class="col-sm-2 control-label">
                                        Correo Admin
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_admin" name="correo_admin" class="form-control" placeholder="Correo Admin"
                                            value="{!! old('correo_admin', $configuracion->correo_admin) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_admin', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('correo_shopmanager', 'has-error') }}">
                                    <label for="correo_shopmanager" class="col-sm-2 control-label">
                                        Correo Shop Manager
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_shopmanager" name="correo_shopmanager" class="form-control" placeholder="Correo Shop Manager"
                                            value="{!! old('correo_shopmanager', $configuracion->correo_shopmanager) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_shopmanager', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('correo_shopmanagercorp', 'has-error') }}">
                                    <label for="correo_shopmanagercorp" class="col-sm-2 control-label">
                                        Correo Shop Manager Corporativo
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_shopmanagercorp" name="correo_shopmanagercorp" class="form-control" placeholder="Correo Shop Manager Corporativo"
                                            value="{!! old('correo_shopmanagercorp', $configuracion->correo_shopmanagercorp) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_shopmanagercorp', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('correo_masterfile', 'has-error') }}">
                                    <label for="correo_masterfile" class="col-sm-2 control-label">
                                        Correo Masterfile
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_masterfile" name="correo_masterfile" class="form-control" placeholder="Correo Masterfile"
                                            value="{!! old('correo_masterfile', $configuracion->correo_masterfile) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_masterfile', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('correo_sac', 'has-error') }}">
                                    <label for="correo_sac" class="col-sm-2 control-label">
                                        Correo Sac
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_sac" name="correo_sac" class="form-control" placeholder="Correo Sac"
                                            value="{!! old('correo_sac', $configuracion->correo_sac) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_sac', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('correo_cedi', 'has-error') }}">
                                    <label for="correo_cedi" class="col-sm-2 control-label">
                                        Correo Cedi
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_cedi" name="correo_cedi" class="form-control" placeholder="Correo Cedi"
                                            value="{!! old('correo_cedi', $configuracion->correo_cedi) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_cedi', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('correo_logistica', 'has-error') }}">
                                    <label for="correo_logistica" class="col-sm-2 control-label">
                                        Correo Logistica
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_logistica" name="correo_logistica" class="form-control" placeholder="Correo Logistica"
                                            value="{!! old('correo_logistica', $configuracion->correo_logistica) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_logistica', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->first('correo_finanzas', 'has-error') }}">
                                    <label for="correo_finanzas" class="col-sm-2 control-label">
                                        Correo Finanzas
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_finanzas" name="correo_finanzas" class="form-control" placeholder="Correo Finanzas"
                                            value="{!! old('correo_finanzas', $configuracion->correo_finanzas) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_finanzas', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->first('correo_ultimamilla', 'has-error') }}">
                                    <label for="correo_ultimamilla" class="col-sm-2 control-label">
                                        Correo UltimaMilla
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_ultimamilla" name="correo_ultimamilla" class="form-control" placeholder="Correo Ultimamilla"
                                            value="{!! old('correo_ultimamilla', $configuracion->correo_ultimamilla) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_ultimamilla', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>



                                 <div class="form-group {{ $errors->first('correo_respuesta', 'has-error') }}">
                                    <label for="correo_respuesta" class="col-sm-2 control-label">
                                        Correo Respuesta
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="correo_respuesta" name="correo_respuesta" class="form-control" placeholder="Correo Respuesta"
                                            value="{!! old('correo_respuesta', $configuracion->correo_respuesta) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('correo_respuesta', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>



                                <div class="form-group {{ $errors->first('nombre_correo_respuesta', 'has-error') }}">
                                    <label for="nombre_correo_respuesta" class="col-sm-2 control-label">
                                       Nombre  Correo Respuesta
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" id="nombre_correo_respuesta" name="nombre_correo_respuesta" class="form-control" placeholder="Nombre  Correo Respuesta"
                                            value="{!! old('nombre_correo_respuesta', $configuracion->nombre_correo_respuesta) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('nombre_correo_respuesta', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>









                            </fieldset>

                            <fieldset>  

                                <legend>Ciudades Permitidas para compras</legend>

                                <div class="row">   

                                         <!-- Select State -->

                                    <div class="form-group col-sm-3 col-xs-12" style="margin: 0 0 15px 0;">
                                        
                                        <label for="select21" class=" control-label">Departamento</label>
                                            
                                            <div class="" >

                                                    <select id="state_id" name="state_id" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($states as $state)

                                                        <option value="{{ $state->id }}">
                                                                {{ $state->state_name}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-3 col-xs-12" style="margin: 0 0 15px 0;">
                                                
                                                <label for="select21" class=" control-label">Ciudad</label>
                                                <div class="" >

                                                    <select id="city_id" name="city_id" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                      
                                                    </select>

                                                </div>

                                            </div>

                                            <div class="form-group col-sm-3 col-xs-12" style="margin: 0 0 15px 0;">
                                                <br>

                                                <button type="button" class="btn btn-default" onclick="addCiudad();"> Agregar </button>
                                            </div>

                                    </div>  

                                    <div class="row">
                                        
                                        <div class="ciudades">
                                            
                                                @if(count($cities))

                                                    <table class="table table-responsive">
                                                        <thead>
                                                            <tr>
                                                                <td>Ciudad</td>
                                                                <td>Accion</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                    @foreach($cities as $ciudad)
                                                       <tr>
                                                           <td>{{ $ciudad->state_name.' - '.$ciudad->city_name }}</td>
                                                           <td>
                                                               <button data-id="{{ $ciudad->id }}" type="button" class="btn btn-danger btn-xs delCiudad"><i class="fa fa-trash"></i></button>
                                                           </td>
                                                       </tr>        

                                                    @endforeach  

                                                  </tbody>
                                                    </table>

                                                @endif


                                                <hr>





                                        </div>

                                    </div>

                            </fieldset>
                      
                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                
                                <a class="btn btn-danger" href="{{ route('admin.configuracion.index') }}">
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
    <!-- row-->
</section>

@stop


@section('footer_scripts')
<script src="https://cdn.tiny.cloud/1/qc49iemrwi4gmrqtiuvymiviycjklawxnqmtcnvorw0hckoj/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>


<script >

    function addCiudad(){

        city_id = $('#city_id').val();
        var base = $('#base').val();

         $.ajax({
            type: "POST",
            data:{ city_id},
            url: base+"/admin/configuracion/storecity",
                
            complete: function(datos){     

                $(".ciudades").html(datos.responseText);

            }
        });



    }




 $(document).ready(function(){
        //Inicio select región

        tinymce.init({
            selector:'#popup_mensaje',
            width: '100%',
            height: 300
        });

            $(document).on('click', '.delCiudad', function(){
                id=$(this).data('id');
        var base = $('#base').val();
                

                $.ajax({
                type: "POST",
                data:{ id},
                url: base+"/admin/configuracion/delcity",
                    
                complete: function(datos){     

                    $(".ciudades").html(datos.responseText);

                }
            });


            });

            //inicio select ciudad
            $('select[name="state_id"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
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
            //fin select ciudad
        });

 </script>

@stop
