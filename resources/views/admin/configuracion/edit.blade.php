@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Configuracion General
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Configuracion General
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Configuracion</li>
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
                       Editar Configuracion
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
                                    Limite de Amigos Alpina
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="limite_amigos" name="limite_amigos" class="form-control" placeholder="Limite de Amigos Alpina"
                                        value="{!! old('limite_amigos', $configuracion->limite_amigos) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('limite_amigos', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('minimo_compra', 'has-error') }}">
                                <label for="minimo_compra" class="col-sm-2 control-label">
                                    Minimo de Compra
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" step="0.01" min="0"  id="minimo_compra" name="minimo_compra" class="form-control" placeholder="Minimo Compra"
                                        value="{!! old('minimo_compra', $configuracion->minimo_compra) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('minimo_compra', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>
                            <hr />
                            <div class="form-group {{ $errors->first('id_mercadopago', 'has-error') }}">
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

                             <div class="form-group {{ $errors->first('comision_mp', 'has-error') }}">
                                <label for="comision_mp" class="col-sm-2 control-label">
                                    % Comision Mercado Pago
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="comision_mp" name="comision_mp" class="form-control" placeholder="% Comision Mercado Pago"
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

                            <div class="form-group  {{ $errors->first('mercadopago_sand', 'has-error') }}">
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
                               
                            </div>

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


                             <div class="form-group  {{ $errors->first('user_activacion', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Activacion de Usuario
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
                                        <input type="text" id="seo_description" name="seo_description" class="form-control" placeholder="SEO Description"
                                            value="{!! old('seo_description', $configuracion->seo_description) !!}">
                                    </div>
                                    <div class="col-sm-4">
                                        {!! $errors->first('seo_description', '<span class="help-block">:message</span> ') !!}
                                    </div>
                                </div>

                            <fieldset>
                                
                                <legend>Correos para notificacion</legend>


                                  <div class="form-group {{ $errors->first('mensaje_bienvenida', 'has-error') }}">
                                    <label for="mensaje_bienvenida" class="col-sm-2 control-label">
                                        Correo Admin
                                    </label>
                                    <div class="col-sm-5">
                                        
                                        <textarea id="mensaje_bienvenida" name="mensaje_bienvenida" cols="30" rows="10" class="form-control" placeholder="Mensaje de Bienvenida">{!! old('mensaje_bienvenida', $configuracion->mensaje_bienvenida) !!}</textarea>
                                       
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
