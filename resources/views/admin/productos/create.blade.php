
@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
 @lang('productos/title.add') :: @parent
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    
    <link href="{{ secure_asset('assets/vendors/acc-wizard/acc-wizard.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/accordionformwizard.css') }}" rel="stylesheet" />
    

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <!-- stilos para la carga de imagen  -->
    <link href="{{ secure_asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    

    <!-- fin stilos para la carga de imagen  -->


<!-- stilos para arbol de categorias -->

    <link href="{{ secure_asset('assets/vendors/jstree/css/style.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ secure_asset('assets/vendors/treeview/css/bootstrap-treeview.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/css/pages/treeview_jstree.css') }}" rel="stylesheet" type="text/css"/>

    <!-- fin stilos para arbol de categorias -->

    <!--end of page level css-->







    <style>
        .dropzone .dz-preview .dz-image img {
            width :100%;
        }

        .select2{
            width: 100%;   height: 2.5em;
        }
    </style>
    
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
                <!--section starts-->
                <h1>@lang('productos/title.add')</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ secure_url('admin') }}">
                            <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                            @lang('general.dashboard')
                        </a>
                    </li>
                    <li>
                        <a href="#">@lang('productos/title.title')</a>
                    </li>
                    <li class="active">@lang('productos/title.add')</li>
                </ol>
            </section>

            <!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="livicon" data-name="gear" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('productos/title.add')
                </h3>
                <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                             <i class="glyphicon glyphicon glyphicon-remove removepanel clickable"></i>
                        </span>
            </div>
            <div class="panel-body">

                     @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


            {!! Form::open(['url' => secure_url('admin\productos'), 'class' => 'form-horizontal', 'id' => 'productosForm', 'name' => 'productosForm', 'files'=> true]) !!}

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                <div class="row acc-wizard">

                    <div class="col-md-3 pd-2">

                        <p class="mar-2">

                           @lang('productos/title.pasos')
                        </p>
                        <ol class="acc-wizard-sidebar">
                            <li class="acc-wizard-todo acc-wizard-active">
                                <a href="#divbasicos">@lang('productos/title.basic')</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#addwizard">@lang('productos/title.description')</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#adjusthtml">@lang('productos/title.seo_ajuste')</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#prod_categoria">@lang('productos/title.categorias')</a>
                            </li>

                            <li class="acc-wizard-todo">
                                <a href="#viewpage">@lang('productos/title.caracteristica')</a>
                            </li>

                            <li class="acc-wizard-todo">
                                <a href="#price_page">@lang('productos/title.prices')</a>
                            </li>

                        
                            
                        </ol>
                    </div>
                    <div class="col-md-9 pd-r">

                        <div id="accordion-demo" class="panel-group">
                            
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#divbasicos" data-parent="#accordion-demo" data-toggle="collapse">Datos Básicos</a>
                                    </h4>
                                </div>
                                <div id="divbasicos" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        
                                            <div class="form-group clearfix {{ $errors->
                            first('nombre_producto', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="nombre_producto">@lang('productos/title.name')</label>
                                                <div class="col-md-9">
                                                    <input id="nombre_producto" name="nombre_producto" type="text" placeholder="Nombre del Producto" class="form-control" value="{{ old('nombre_producto') }}">

                                                    {!! $errors->first('nombre_producto', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>
                                            <div class="form-group clearfix {{ $errors->
                            first('presentacion_producto', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="presentacion_producto">@lang('productos/title.prese')</label>
                                                <div class="col-md-9">
                                                    <input id="presentacion_producto" name="presentacion_producto" type="text" placeholder="Presentación del Producto" class="form-control" value="{{ old('presentacion_producto') }}">

                                                    {!! $errors->first('presentacion_producto', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>
                                            <div class="form-group clearfix {{ $errors->
                            first('referencia_producto', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.reference')</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Referencia del PRoducto" class="form-control" value="{{ old('referencia_producto') }}">

                                                    {!! $errors->first('referencia_producto', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>
                                            <div class="form-group clearfix {{ $errors->
                            first('referencia_producto_sap', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.reference_sap')</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto_sap" name="referencia_producto_sap" type="text" placeholder="Referencia Sap" value="{{ old('referencia_producto_sap') }}" class="form-control">

                                                    {!! $errors->first('referencia_producto_sap', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>



                                            <div class="form-group col-sm-12  {{ $errors->first('tipo_producto', 'has-error') }}">

                                                <label for="select21" class="col-sm-3 col-xs-12 control-label">
                                                    @lang('productos/title.tipo')
                                                </label>

                                                <div class="col-sm-9 col-xs-12" >

                                                    <select style="width: 100%; height: 2.5em;" id="tipo_producto" name="tipo_producto" class="form-control  select2 {{ $errors->first('tipo_producto', 'has-error') }}  ">

                                                        
                                                        <option value="1"  >Normal</option>

                                                         <option value="2"  >Combo</option>
                                                       

                                                    </select>
                                                        {!! $errors->first('tipo_producto', '<span class="help-block">:message</span>') !!}
                                                </div>
                                                
                                            </div>


                                            <div class="acc-wizard-step"></div>

                                             <a id="panel_combo" class="btn btn-default" href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>
                                        
                                    </div>

                                </div>

                            </div>
                            <!-- /.panel.panel-default -->



                            <div class="panel panel-success @if(old('tipo_producto')!=null) @if(old('tipo_producto')==1) {{ 'hidden' }}  @endif @else {{'hidden'}} @endif" id="panelComboProductos">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#addProductos" data-parent="#accordion-demo" data-toggle="collapse">Incluir Productos</a>
                                    </h4>
                                </div>
                                <div id="addProductos" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        
                                        
                                        <div class="form-group col-sm-12 {{ $errors->first('id_impuesto', 'has-error') }}">
                                            <label for="select21" class="col-md-3 control-label">
                                               Productos
                                            </label>
                                            <div class="col-md-7">   
                                             <select style="width: 100%; height: 2.5em;" style="width: 50%" id="id_producto" name="id_producto" class="form-control select2 js-example-responsive">
                                                <option value="">Seleccione</option>
                                                    
                                                    @foreach($productos as $pro)

                                                        @if(isset($inventario[$pro->id]))
                                                        
                                                        <option value="{{ $pro->id }}">{{ $pro->nombre_producto.' - '.$pro->referencia_producto.' - Disp:'.$inventario[$pro->id]}}
                                                        </option>

                                                        @else

                                                            <option value="{{ $pro->id }}">{{ $pro->nombre_producto.' - '.$pro->referencia_producto.' - Disp: 0'}}
                                                        </option>

                                                        @endif

                                                    @endforeach

                                            </select>

                                              {!! $errors->first('id_producto', '<span class="help-block">:message</span> ') !!}
                                            </div>


                                            <div class="col-md-2">   

                                                <button type="button" class="btn btn-primary addProductoCupon" > Agregar</button>
                                             

                                              
                                            </div>
                                               
                                        </div>


                                        <div class="col-sm-12 listaProducos"> 

                                                
                                            <table class="table table-responsive" id="tableListProductos">
                                                <thead>
                                                    <tr>
                                                        <td>Producto</td>
                                                        <td>Cantidad</td>
                                                        <td>Accion</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>    


                                        </div>
                                           


                                            <div class="acc-wizard-step"></div>

                                             <a class="btn btn-default" href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>
                                        
                                    </div>

                                </div>

                            </div>
                            <!-- /.panel.panel-default -->









                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.description')</a>
                                    </h4>
                                </div>
                                <div id="addwizard" class="panel-collapse collapse awd-h" style="height: 36.400001525878906px;">
                                    <div class="panel-body">
                                        
                                        <div class="form-group clearfix {{ $errors->
                            first('descripcion_corta', 'has-error') }}">
                                            <label class="col-md-3 control-label" for="descripcion_corta">@lang('productos/title.description_sort')</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_corta" name="descripcion_corta" placeholder="Descripcion Corta" rows="5">{{ old('descripcion_corta') }}</textarea>

                                                {!! $errors->first('descripcion_corta', '<span class="help-block">:message</span> ') !!}
                                            </div>
                                        </div>
                                        <div class="form-group clearfix {{ $errors->
                            first('descripcion_larga', 'has-error') }}">
                                            <label class="col-md-3 control-label" for="descripcion_larga">@lang('productos/title.description_large')</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_larga" name="descripcion_larga" placeholder="Descripcion Larga" rows="5">{{ old('descripcion_corta') }}</textarea>

                                                {!! $errors->first('descripcion_larga', '<span class="help-block">:message</span> ') !!}
                                            </div>
                                        </div>


                                        <div class="form-group clearfix {{ $errors->
                            first('enlace_youtube', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="enlace_youtube">Video YouTube</label>
                                                <div class="col-md-9">
                                                    <input id="enlace_youtube" name="enlace_youtube" type="text" placeholder="Video YouTube" class="form-control" value="{{ old('enlace_youtube') }}">

                                                    {!! $errors->first('enlace_youtube', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>







                        <div class="form-group clearfix">

                            <label for="title" class="col-md-3 control-label">Imagen de Producto</label>


                            <div class="col-md-9">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    <img src="{{ secure_asset('assets/images/authors/no_avatar.jpg') }}" alt="..."class="img-responsive"/>

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px;">
                                         
                                </div>

                                <div>
                                    <span class="btn btn-primary btn-file">

                                        <span class="fileinput-new">Seleccione Imagen </span>

                                        <span class="fileinput-exists">Cambiar</span>

                                        <input type="file" name="image" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Eliminar</span>

                                </div>

                            </div>
                            </div>

                        </div>


                                        <!-- Carga de imagenes -->
                                        <div class="row">   
                                         <div class="col-md-12" style="padding:30px;">
                                        
                                        </div>
                                        </div>



                                            <div class="acc-wizard-step"></div>

                                            <a class="btn btn-default" href="#divbasicos" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.previous')</a>


                                        <a class="btn btn-default" href="#adjusthtml" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>
                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#addwizard --> </div>
                            <!-- /.panel.panel-default -->

                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#adjusthtml" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.seo_ajuste')</a>
                                    </h4>
                                </div>
                                <div id="adjusthtml" class="panel-collapse collapse" style="height: 36.400001525878906px;">
                                    <div class="panel-body">
                                          
                                            <div class="form-group clearfix {{ $errors->
                            first('seo_titulo', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="nombre_producto">@lang('productos/title.seo_title')</label>
                                                <div class="col-md-9">
                                                    <input id="seo_titulo" name="seo_titulo" type="text" placeholder="Seo Titulo" class="form-control" value="{{ old('seo_titulo') }}">

                                                    {!! $errors->first('seo_titulo', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>
                                            <div class="form-group clearfix {{ $errors->
                            first('seo_descripcion', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.seo_des')</label>
                                                <div class="col-md-9">
                                                    <input id="seo_descripcion" name="seo_descripcion" type="text" placeholder="Seo Descripcion" class="form-control" maxlength="160" value="{{ old('seo_descripcion') }}">

                                                     {!! $errors->first('seo_descripcion', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                            </div>
                                            <div class="form-group clearfix {{ $errors->
                            first('slug', 'has-error') }}">
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.slug')</label>
                                                <div class="col-md-9">
                                                    <input id="slug" name="slug" type="text" placeholder="Seo Url" class="form-control" value="{{ old('slug') }}">
                                                    {!! $errors->first('slug', '<span class="help-block">:message</span> ') !!}
                                                </div>

                                                    
                                            </div>

                                            <div class="col-sm-12">

                                            <fieldset>
                            
                                                <h3>Opciones robots.</h3>

                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_all" name="robots_all" value="all" >
                                                   All
                                                  </label>
                                                </div>

                                                <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_index" name="robots_index" value="index"   checked  >
                                                       Index
                                                      </label>
                                                    </div>

                                                    <div class="checkbox">
                                                      <label>
                                                        <input type="checkbox" id="robots_follow" name="robots_follow" value="follow"   checked  >
                                                       Follow
                                                      </label>
                                                    </div>




                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_noindex" name="robots_noindex" value="noindex">
                                                   noindex
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_nofollow" name="robots_nofollow" value="nofollow">
                                                   nofollow
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_none" name="robots_none" value="none">
                                                   none
                                                  </label>
                                                </div>

                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_noarchive" name="robots_noarchive" value="noarchive">
                                                   noarchive
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_nosnippet" name="robots_nosnippet" value="nosnippet">
                                                   nosnippet
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_notranslate" name="robots_notranslate" value="notranslate">
                                                   notranslate
                                                  </label>
                                                </div>


                                                <div class="checkbox">
                                                  <label>
                                                    <input type="checkbox" id="robots_noimageindex" name="robots_noimageindex" value="noimageindex">
                                                   noimageindex
                                                  </label>
                                                </div>



                                                </fieldset>


                                            </div>









                                            <div class="acc-wizard-step"></div>

                                            <a class="btn btn-default" href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.previous')</a>


                                            <a class="btn btn-default" href="#prod_categoria" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>
                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#adjusthtml --> </div>
                            <!-- /.panel.panel-default -->


                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#prod_categoria" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.categorias')</a>
                                    </h4>
                                </div>
                                <div id="prod_categoria" class="panel-collapse collapse" style="height: 36.400001525878906px;">
                                    <div class="panel-body">

                                        <input type="hidden" name="tree" id="tree" value="{{ $tree }}">
                                        <input type="hidden" name="categorias_prod" id="categorias_prod" value="">
                                        <input type="hidden" name="categorias_prod_check" id="categorias_prod_check" value="{{ $check }}">

                                        <div class="col-sm-12">
                                            <label>@lang('productos/title.tree') </label>
                                            <div id="treeview-checkable" class=""></div>

                                            <a class="btn btn-default" href="#adjusthtml" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.previous')</a>


                                            <a class="btn btn-default" href="#viewpage" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>


                                        </div>
                                        
                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#adjusthtml --> </div>


                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#viewpage" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.caracteristica')</a>
                                    </h4>
                                </div>
                                <div id="viewpage" class="panel-collapse collapse" style="height: 36.400001525878906px;">
                                    <div class="panel-body">

                                        <div class="form-group col-sm-12 {{ $errors->
                            first('id_impuesto', 'has-error') }}">
                                                <label for="select21" class="col-md-3 control-label">
                                                    @lang('productos/title.tax')
                                                </label>
                                                <div class="col-md-9">   
                                                 <select style="width: 100%; height: 2.5em;" id="id_impuesto" name="id_impuesto" class="form-control select2 ">
                                                    <option value="">Seleccione</option>
                                                        
                                                         @foreach($impuestos as $imp)
                                                        <option value="{{ $imp->id }}"
                                                                @if($imp->id == old('id_impuesto')) selected="selected" @endif >{{ $imp->nombre_impuesto}}</option>
                                                        @endforeach
                                                </select>

                                                  {!! $errors->first('id_impuesto', '<span class="help-block">:message</span> ') !!}
                                                </div>
                                               
                                            </div>
                                        

                                            <div class="form-group col-sm-12 {{ $errors->
                            first('id_categoria_default', 'has-error') }}">
                                                <label for="select21" class="col-md-3 control-label">
                                                    @lang('productos/title.category_default')
                                                </label>
                                                <div class="col-md-9">   
                                                 <select style="width: 100%; height: 2.5em;" id="id_categoria_default" name="id_categoria_default" class="form-control select2 ">
                                                    <option value="">Seleccione</option>
                                                        
                                                         @foreach($categorias as $cat)
                                                        <option value="{{ $cat->id }}"
                                                                @if($cat->id == old('id_categoria_default')) selected="selected" @endif >{{ $cat->nombre_categoria}}</option>
                                                        @endforeach
                                                </select>

                                                  {!! $errors->first('id_categoria_default', '<span class="help-block">:message</span> ') !!}
                                                </div>
                                               
                                            </div>

                                            <div class="form-group col-sm-12 {{ $errors->
                            first('id_marca', 'has-error') }}">
                                                <label for="select21" class="col-md-3 control-label">
                                                    @lang('productos/title.marca')
                                                </label>
                                                <div class="col-md-9" >
                                                    <select style="width: 100%; height: 2.5em;" id="id_marca" name="id_marca" class="form-control select2 ">
                                                        <option value="">Seleccione</option>
                                                       
                                                        @foreach($marcas as $marca)
                                                        <option value="{{ $marca->id }}"
                                                                @if($marca->id == old('id_marca')) selected="selected" @endif >{{ $marca->nombre_marca}}</option>
                                                        @endforeach
                                                      
                                                    </select>

                                                    {!! $errors->first('id_marca', '<span class="help-block">:message</span> ') !!}
                                                </div>

                                               
                                            </div>


                                            <div class="form-group clearfix col-sm-12 {{ $errors->
                                                first('pum', 'has-error') }}">
                                                
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.inventary') </label>
                                                
                                                <div class="col-md-9">
                                                    <input id="inventario_inicial" name="inventario_inicial" type="number" placeholder="Inventario Inicial" class="form-control" value="{{ old('inventario_inicial') }}" >

                                                    {!! $errors->first('inventario_inicial', '<span class="help-block">:message</span> ') !!}

                                                </div>

                                                
                                            </div>

                                            <div class="form-group clearfix col-sm-12 {{ $errors->
                                                first('pum', 'has-error') }}">
                                                
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.pum') </label>
                                                
                                                <div class="col-md-9">
                                                    <input id="pum" name="pum" type="text" placeholder="Pum" class="form-control" value="{{ old('pum') }}" >

                                                    {!! $errors->first('pum', '<span class="help-block">:message</span> ') !!}

                                                </div>

                                                
                                            </div>

                                            <div class="form-group col-sm-12 clearfix {{ $errors->first('medida', 'has-error') }}">

                                                <label class="col-sm-3 col-xs-12 control-label" for="referencia_producto">@lang('productos/title.medida') </label>

                                                <div class="col-sm-9 col-xs-12">
                                                    <input id="medida" name="medida" type="text" placeholder="Medida" class="form-control" value=""  >

                                                     {!! $errors->first('medida', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                                <br>    

                                              

                                            </div>


                                            <div class="form-group col-sm-12 clearfix {{ $errors->first('unidad', 'has-error') }}">

                                                <label class="col-sm-3 col-xs-12 control-label" for="referencia_producto">Unidad de Medida</label>

                                                <div class="col-sm-9 col-xs-12">
                                                     <select id="unidad" name="unidad" class="form-control ">
                                                        @foreach($unidades as $u)
                                                           
                                                            <option value="{{ $u->nombre_unidad }}"
                                                                    >{{$u->nombre_unidad}}</option>


                                                                    @endforeach

                                                            
                                                           
                                                    </select>

                                                     {!! $errors->first('unidad', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                                <br>    

                                              

                                            </div>


                                            <div class="form-group col-sm-12 clearfix {{ $errors->first('cantidad', 'has-error') }}">

                                                <label class="col-sm-3 col-xs-12 control-label" for="referencia_producto">Cantidad del Producto </label>

                                                <div class="col-sm-9 col-xs-12">
                                                    <input id="cantidad" name="cantidad" type="text" placeholder="Cantidad del Producto" class="form-control" value=""  >

                                                     {!! $errors->first('cantidad', '<span class="help-block">:message</span> ') !!}

                                                </div>
                                                <br>    

                                              

                                            </div>




                                           
                        
                                           
                                            <div class="acc-wizard-step">
                                                
                                            </div>

                                            <a class="btn btn-default" href="#prod_categoria" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.previous')</a>

                                            <a class="btn btn-default" href="#price_page" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>
                                            
                                            
                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#adjusthtml --> </div>


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#price_page" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.prices')</a>
                                </h4>
                            </div>

                            <div id="price_page" class="panel-collapse collapse" style="height: 36.400001525878906px;">
                                <div class="panel-body">

                            
                                                            
                                <div class="form-group col-sm-12  {{ $errors->first('mostrar_descuento', 'has-error') }}">
                                            <label for="select21" class="col-sm-3 control-label">
                                                Mostrar Descuento 
                                            </label>
                                            <div class="col-sm-9">   
                                             <select id="mostrar_descuento" name="mostrar_descuento" class="form-control ">
                                                <option value="">Seleccione</option>
                                                   
                                                    <option value="{{ 1 }}"
                                                             >Mostrar</option>

                                                    <option value="{{ 0}}"
                                                           >No Mostrar</option>
                                                   
                                            </select>

                                            {!! $errors->first('mostrar_descuento', '<span class="help-block">:message</span> ') !!}
                                            
                                              
                                            </div>
                                           
                                        </div>



                                    <div class="form-group clearfix col-sm-12 {{ $errors->
                                        first('precio_base', 'has-error') }}">
                                       <label class="col-md-3 control-label producto_label" for="referencia_producto">@lang('productos/title.price') </label>
                                        <div class="col-md-9">
                                            <input id="precio_base" step="0.01" name="precio_base" type="number" placeholder="Precio" class="form-control" value="{{ old('precio_base') }}"  >{!! $errors->first('precio_base', '<span class="help-block">:message</span> ') !!}
                                        </div>
                                    </div>

                                    <div class="row">

                                        <h4>    Definir precios especificos 
                                        </h4>
                                        
                                        <div class="col-sm-12">
                                        

                                            <div class="form-group col-sm-3" style="margin: 0 0 15px 0;">

                                                <label for="select21" class=" control-label">
                                                    @lang('productos/title.roles') o Corporativo
                                                </label>

                                                <div class="" >

                                                    <select style="width: 100%; height: 2.5em;" id="role_precio" name="role_precio" class="form-control ">
                                                        <option value="">Seleccione</option>

                                                       <optgroup label="Roles">
                                                        @foreach($roles as $rol)
                                                        <option value="{{ $rol->id.'_'.$rol->name }}"
                                                                @if($rol->id == old('role_precio')) selected="selected" @endif >{{ $rol->name}}</option>
                                                        @endforeach
                                                        </optgroup>

                                                        <optgroup label="Corporativos">
                                                        @foreach($empresas as $empresa)
                                                        <option value="{{ 'E'.$empresa->id.'_'.$empresa->nombre_empresa }}"
                                                                @if($empresa->id == old('role_precio')) selected="selected" @endif >{{ $empresa->nombre_empresa}}</option>
                                                        @endforeach
                                                        </optgroup>
                                                      
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Select State -->

                                            <div class="form-group col-sm-3" style="margin: 0 0 15px 0;">
                                                <label for="select21" class=" control-label">
                                                    Estado
                                                </label>
                                                <div class="" >
                                                    <select style="width: 100%; height: 2.5em;" id="state_id" name="state_id" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($states as $state)

                                                        <option value="{{ $state->id.'_'.$state->state_name }}"
                                                                @if($state->id == old('state_id')) selected="selected" @endif >{{ $state->state_name}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-3" style="margin: 0 0 15px 0;">
                                                <label for="select21" class=" control-label">
                                                    Ciudad
                                                </label>
                                                <div class="" >
                                                    <select style="width: 100%; height: 2.5em;" id="city_id" name="city_id" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                       
                                                        
                                                      
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-3" style="margin: 0 0 15px 0;">
                                                <br>
                                                <button type="button" class="btn btn-default" onclick="addPriceRolEstate();"> Agregar    </button>
                                            </div>



                                        </div>

                                    </div>

                                    <div class="row" id="div_productos">   

                                    </div>

                                            <!--  modelo a clonar-->

                                            <div class="col-sm-12 hidden">  

                                                <div class="form-group clearfix col-sm-12 producto_element">
                                                <label class="col-sm-2 col-xs-12 control-label producto_label" for="referencia_producto">@lang('productos/title.price') </label>

                                                <div class="form-group col-sm-3" style="margin: 0 0 15px 0;">
                                               
                                                    <div class="" >

                                                        <select style="width: 100%; height: 2.5em;" id="test_tipo" name="test_tipo" class="form-control test_tipo">

                                                            <option value="1" Selected>Dejar Precio Base</option>
                                                            <option value="2">Porcentaje Descuento</option>
                                                            <option value="3">Valor Fijo</option>

                                                        </select>       
                                                    </div>

                                                </div>

                                                
                                                <div class="col-sm-2">
                                                    <input id="test_precio" step="0.01" name="test_precio" type="number" placeholder="Valor" class="form-control" readonly="" value="{{ old('precio_role') }}"  >

                                                    <h3><span class="label label-success ">Precio  </span></h3>
                                                </div>

                                                <div class="col-sm-2">
                                                    <input id="test_pum"  name="test_pum" type="text" placeholder="PUM" class="form-control" value="{{ old('pum') }}"  >
                                                    
                                                </div>
                                                
                                                <div class="form-group col-sm-2" style="margin: 0 0 15px 0;">
                                               
                                                    <div class="" >

                                                        <select style="width: 100%; height: 2.5em;" id="test_descuento" name="test_descuento" class="form-control ">

                                                            <option value="1" Selected>Mostrar </option>
                                                            <option value="0">No Mostrar</option>

                                                        </select>       
                                                    </div>

                                                </div>

                                                <div class="col-sm-1">
                                                    <button class="btn btn-xs btn-danger">x</button>
                                                </div>
                                            </div>

                                             </div>
                                           
                                            <div class="acc-wizard-step">
                                                
                                            </div>

                                            <a class="btn btn-default" href="#viewpage" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.previous')</a>
                                            
                                            <button type="button" class="btn btn-primary finish">@lang('button.save') </button>
                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                         </div><!-- end panel -->



                            <!-- /.panel.panel-default --> </div>
                    </div>
                </div>
            {!! Form::close() !!}

             <a class="btn btn-default" href="{{ secure_url('/admin/productos') }}">@lang('button.back')</a>
            </div>
        </div>
    </div>
    <!--main content ends--> </section>
            <!-- content --> 
    @stop

{{-- page level scripts --}}
@section('footer_scripts')

    <!-- js para la carga de imahenes  -->
<script src="{{ secure_asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

    <!-- fin  js para la carga de imahenes  -->

    
    <script src="{{ secure_asset('assets/vendors/acc-wizard/acc-wizard.min.js') }}" ></script>
    <script src="{{ secure_asset('assets/js/pages/accordionformwizard.js') }}"  type="text/javascript"></script>

    
    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


   


     <!-- Arbol de categorias -->
    <script src="{{ secure_asset('assets/vendors/jstree/js/jstree.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/treeview/js/bootstrap-treeview.min.js') }}" type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>
   <!-- <script src="{{ secure_asset('assets/js/pages/treeview_jstree.js') }}" type="text/javascript"></script>-->

    <script type="text/javascript">

       
        $(document).ready(function(){

               $(document).on('change', '.selectprecio', function(e) {

                    precio_base=$('#precio_base').val();

                    ele=$(this);

                    valor=ele.val();

                    rc=ele.data('rc');
                    

                    if (valor==1) {

                        $('#rolprecio_'+rc+'').attr('readonly','true');

                        $('.spanprecio_'+rc+'').html('Precio para la seleccion: '+precio_base);

                    }

                    if (valor==2) {

                        $('#rolprecio_'+rc+'').removeAttr('readonly');

                        $('#rolprecio_'+rc+'').val('');

                       

                    }

                     if (valor==3) {

                        $('#rolprecio_'+rc+'').removeAttr('readonly','false');

                        $('#rolprecio_'+rc+'').val('');

                       

                    }


                });

                $(document).on('keyup', '.rolprecio', function(e) {

                    precio_base=$('#precio_base').val();

                    ele=$(this);

                    valor=ele.val();

                    rc=ele.data('rc');

                    valor_select=$('#select_'+rc+'').val();
                    

                    if (valor_select==1) {

                        $('#rolprecio_'+rc+'').attr('readonly','true');

                        $('.spanprecio_'+rc+'').html('Precio para la seleccion: '+precio_base);
                       

                    }

                    if (valor_select==2) {

                        $('#rolprecio_'+rc+'').removeAttr('readonly');

                        if (valor>100) {

                            valor=0;

                            ele.val('');

                            let total=precio_base*(1-(valor/100));

                            $('.spanprecio_'+rc+'').html('Precio para la seleccion: '+total);

                        }else{

                            let total=precio_base*(1-(valor/100));

                        $('.spanprecio_'+rc+'').html('Precio para la seleccion: '+total);

                        }

                        

                       

                    }

                     if (valor_select==3) {

                        $('#rolprecio_'+rc+'').removeAttr('readonly','false');


                        $('.spanprecio_'+rc+'').html('Precio para la seleccion: '+valor);

                       

                    }


                });

                 $(document).on('click', '.delprecio', function(e) {

                    ele=$(this);

                    rc=ele.data('rc');                    
                    
                    $('.element_'+rc+'').remove();
                   
                });



            });

            //alert('alerta');

           // valor=$(this).val();

        function addPriceRolEstate(){

            role=$('#role_precio').val();

            state=$('#state_id').val();

            city=$('#city_id').val();

            precio_base=$('#precio_base').val();


            if (role=='' || role==undefined || state=='' || state==undefined || city=='' || city==undefined )  {

                alert('todos los campos son requeridos ');

            }else{

                role_separado=role.split('_');

                state_separado=state.split('_');

                city_separado=city.split('_');


                if ( $('#rolprecio_'+role_separado[0]+'_'+city_separado[0]+'').length ) {

                     alert('Ya existe esta opcion');

                }else{


                    ele=$('.producto_element').clone();

                    ele.removeClass('producto_element');

                    ele.addClass('element_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('label').html('Precio para el '+role_separado[1]+' '+city_separado[1]+'');
                    
                   ele.find('#test_precio').attr('name', 'rolprecio_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('#test_precio').attr('data-rc', role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('#test_precio').addClass('rolprecio');
                    
                    ele.find('#test_precio').attr('id', 'rolprecio_'+role_separado[0]+'_'+city_separado[0]+'');


                    ele.find('.test_tipo').attr('name', 'select_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('.test_tipo').attr('id', 'select_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('.test_tipo').attr('data-rc', role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('.test_tipo').addClass('selectprecio');


                    
                    ele.find('#test_pum').attr('name', 'rolpum_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('#test_pum').attr('data-rc', role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('#test_pum').addClass('rolpum');

                    ele.find('#test_pum').attr('id', 'rolpum_'+role_separado[0]+'_'+city_separado[0]+'');


                    ele.find('#test_descuento').attr('name', 'roldescuento_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('#test_descuento').attr('data-rc', role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('#test_descuento').addClass('roldescuento');

                    ele.find('#test_descuento').attr('id', 'roldescuento_'+role_separado[0]+'_'+city_separado[0]+'');

                    

                    ele.find('span').addClass('spanprecio_'+role_separado[0]+'_'+city_separado[0]+'');

                    ele.find('span').html('Precio para la seleccion: '+precio_base);

                    ele.find('button').addClass('delprecio');

                    ele.find('button').attr('data-rc', role_separado[0]+'_'+city_separado[0]+'');

                                   
                    $('#div_productos').append(ele);

                 }

            }
           
        }


          function verificarCategorias (){

            cat='';            

            $(".node-checked").each(function(){

                    t=$(this).text().split('-', 1);  

                    cat=cat+t+',';

                });       

            cat=cat.slice(0, -1);     

            $('#categorias_prod').val(cat);               
              
        }

        $(document).ready(function(){

        defaultData=$("#tree").val();

        var $checkableTree = $('#treeview-checkable').treeview({
            data: defaultData,
            showIcon: false,
            showCheckbox: true,
            onNodeChecked: function(event, node) {
                $('#checkable-output').prepend('<p>' + node.text + ' was checked</p>');
            },
            onNodeUnchecked: function(event, node) {
                $('#checkable-output').prepend('<p>' + node.text + ' was unchecked</p>');

            }
        });


        cc=$('#categorias_prod_check').val();

        c=cc.split(',');

            jQuery.each(c, function(i, val){


                 b=$checkableTree.treeview('search', [val, { ignoreCase: false, exactMatch: false }]);

               $checkableTree.treeview('checkNode', [b, { silent: $('#chk-check-silent').is(':checked') }]);


            } );

           

        });

    </script>


    <script type="text/javascript">
        
base=$('#base').val();        


$(document).on('click', '.delProductoCombo', function(){

    id=$(this).data('id');

    $('#tr'+id+'').remove();
});



$('.addProductoCupon').click(function(){

    id_producto=$('#id_producto').val();

    name=$('select[name="id_producto"] option:selected').text();

    cantidad='1';

    include='';

    if (id_producto !='') {

        if ( $("#tr"+id_producto+"").length ) {

            cantidad=$('#c_can_'+id_producto).val();

            cantidad=parseInt(cantidad)+1;

            $('#c_can_'+id_producto).val(cantidad);

        }else{

            include=include+'<tr id="tr'+id_producto+'">';

            include=include+'<td>'+name+'</td>';
            include=include+'<td> <input type="number" step="1" min="1" name="c_can_'+id_producto+'" id="c_can_'+id_producto+'" value="'+cantidad+'">  </td>';

            include=include+'<td> <button data-id="'+id_producto+'" class="btn btn-danger delProductoCombo"><i class="fa fa-trash "></i></button>';

            include=include+' <input type="hidden" name="c_pro_'+id_producto+'" id="c_pro_'+id_producto+'" value="'+id_producto+'"> </td>';

            include=include+'</tr>';

        }

        $('#tableListProductos tbody').append(include);
        
    }

});




$("#productosForm").bootstrapValidator({
    fields: {
        nombre_producto: {
            validators: {
                notEmpty: {
                    message: 'Nombre de Producto es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        referencia_producto: {
            validators: {
                notEmpty: {
                    message: 'The referencia_producto name is required'
                },
                remote: {
                message: 'La referencia no esta disponible',
                method: 'POST',
                url: base+'/admin/productos/verificar/referencia/',
            }
            },
            required: true,
            minlength: 3
            
        },
        referencia_producto_sap: {
            validators: {
                notEmpty: {
                    message: 'The referencia_producto_sap name is required'
                },
                remote: {
                            message: 'La referencia_producto_sap no esta disponible',
                            method: 'POST',
                            url: base+'/admin/productos/verificar/referenciasap/',
                }
            },
            required: true,
            minlength: 3
        },
        descripcion_corta: {
            validators: {
                notEmpty: {
                    message: 'descripcion_corta is required and cannot be empty'
                }
            },
            minlength: 20
        },
        descripcion_larga: {
            validators: {
                notEmpty: {
                    message: 'descripcion_corta is required and cannot be empty'
                }
            },
            minlength: 20
        },
        seo_titulo: {
            validators: {
                notEmpty: {
                    message: 'The seo_titulo name is required'
                }
            },
            required: true,
            minlength: 3
        },
        seo_descripcion: {
            validators: {
                notEmpty: {
                    message: 'The seo_descripcion name is required'
                }
            },
            required: true,
            minlength: 3
        },
        seo_url: {
            validators: {
                notEmpty: {
                    message: 'The seo_url name is required'
                }
            },
            required: true,
            minlength: 3
        },

        id_categoria_default: {
            validators: {
                notEmpty: {
                    message: 'Please select a id_categoria_default'
                }
            }
        },

        id_marca: {
            validators:{
                notEmpty:{
                    message: 'You must select a id_marca'
                }
            }
        }
    }
});



$('.finish').click(function () {
    verificarCategorias();
    var $validator = $('#productosForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("productosForm").submit();
    }

});
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });
$('#productosForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });



    $(document).ready(function(){
        $('.select21').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })

    function setprecio(){

        var precio=$('#precio_base').val();

        $('.rolprecio').each(function(){

            $(this).val(precio);

        });

    }
    

  

     $(document).ready(function(){
        //Inicio select región
                

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




$('#tipo_producto').change(function(){

    if ($(this).val()=='1') {

        $('#panelComboProductos').addClass('hidden');

         $('#panel_combo').attr('href', '#addwizard');

    }else{

        $('#panelComboProductos').removeClass('hidden');

        $('#panel_combo').attr('href', '#addProductos');


    }

});


     </script>




@stop
