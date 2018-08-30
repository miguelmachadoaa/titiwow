@extends('admin/layouts/default')

@section('title')
Productos 
@parent
@stop

@section('header_styles')
    
    <link href="{{ asset('assets/vendors/acc-wizard/acc-wizard.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/accordionformwizard.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <style>
        .dropzone .dz-preview .dz-image img {
            width :100%;
        }
    </style>
    
@stop


@section('content')
  @include('core-templates::common.errors')
    <section class="content-header">
     <h1>Productos Edit</h1>
     <ol class="breadcrumb">
         <li>
             <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                 Dashboard
             </a>
         </li>
         <li>Productos</li>
         <li class="active">Edit Productos </li>
     </ol>
    </section>
    <section class="content paddingleft_right15">
      <div class="row">
      <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Editar  Producto
                </h4></div>
            <br />
        <div class="panel-body">
       

        {!! Form::model($producto, ['url' => URL::to('admin/productos/'. $producto->id.''), 'method' => 'put', 'id'=>'productosForm']) !!}

      
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="row acc-wizard">
                    <div class="col-md-3 pd-2">
                        <p class="mar-2">
                            Follow the steps below to add an accordion wizard to your web page.
                        </p>
                        <ol class="acc-wizard-sidebar">
                            <li class="acc-wizard-todo acc-wizard-active">
                                <a href="#divbasicos">Datos Basicos</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#addwizard">Descripción</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#adjusthtml">Ajustes SEO</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#viewpage">Caracteristicas Producto</a>
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

                                            <div class="form-group clearfix {{ $errors->first('nombre_producto', 'has-error') }} ">

                                                <label class="col-md-3 control-label" for="nombre_producto">Nombre del Producto</label>

                                                <div class="col-md-9">

                                                    <input id="nombre_producto" name="nombre_producto" type="text" placeholder="Nombre del producto" class="form-control  {{ $errors->first('nombre_producto', 'has-error') }}" value="{!! old('nombre_producto', $producto->nombre_producto) !!}">
                                                </div>
                                                 {!! $errors->first('nombre_producto', '<span class="help-block">:message</span>') !!}

                                            </div>

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia</label>

                                                <div class="col-md-9">

                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Referencia" class="form-control  {{ $errors->first('referencia_producto', 'has-error') }}" value="{!! old('Referencia Sap', $producto->referencia_producto_sap) !!}" value="{!! old('referencia', $producto->referencia_producto) !!}" >
                                                </div>
                                                 {!! $errors->first('referencia_producto', '<span class="help-block">:message</span>') !!}
                                            </div>

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia Sap</label>

                                                <div class="col-md-9">

                                                    <input id="referencia_producto_sap" name="referencia_producto_sap" type="text" placeholder="Your email" class="form-control  {{ $errors->first('referencia_producto_sap', 'has-error') }}" value="{!! old('Referencia Sap', $producto->referencia_producto_sap) !!}">

                                                </div>

                                                {!! $errors->first('referencia_producto_sap', '<span class="help-block">:message</span>') !!}
                                            </div>

                                            <div class="acc-wizard-step"></div>

                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#prerequisites --> </div>
                            <!-- /.panel.panel-default -->

                            <div class="panel panel-info">

                                <div class="panel-heading">

                                    <h4 class="panel-title">

                                        <a href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">Descripción</a>

                                    </h4>

                                </div>

                                <div id="addwizard" class="panel-collapse collapse awd-h" style="height: 36.400001525878906px;">

                                    <div class="panel-body">

                                        <div class="form-group clearfix">

                                            <label class="col-md-3 control-label" for="descripcion_corta">Descripción Corta</label>

                                            <div class="col-md-9">

                                                <textarea class="form-control {{ $errors->first('descripcion_corta', 'has-error') }}  resize_vertical" id="descripcion_corta" name="descripcion_corta" placeholder="Please enter your message here..." rows="5">{!! old('Descripción Corta', $producto->descripcion_corta) !!}</textarea>

                                            </div>

                                            {!! $errors->first('descripcion_corta', '<span class="help-block">:message</span>') !!}

                                        </div>

                                        <div class="form-group clearfix">

                                            <label class="col-md-3 control-label" for="descripcion_larga">Descripción Larga</label>

                                            <div class="col-md-9">

                                                <textarea class="form-control  {{ $errors->first('descripcion_larga', 'has-error') }} resize_vertical" id="descripcion_larga" name="descripcion_larga" placeholder="Please enter your message here..." rows="5">{!! old('Descripción Larga', $producto->descripcion_larga) !!}</textarea>

                                            </div>

                                            {!! $errors->first('descripcion_larga', '<span class="help-block">:message</span>') !!}

                                        </div>

                                        <!-- Carga de imagenes -->

                                        <div class="row">   

                                         <div class="col-md-12" style="padding:30px;">

                                           <!-- {!! Form::open(array('url' => URL::to('admin/file/create'), 'method' => 'post', 'id'=>'myDropzone','class' => 'dropzone', 'files'=> true)) !!}
                                            <div class="fallback">
                                                <input name="file" type="file" multiple />
                                            </div>
                                            {!! Form::close() !!}-->

                                        </div>

                                        </div>

                                            <div class="acc-wizard-step"></div>
                                        
                                    </div>
                                    <!--/.panel-body --> </div>

                                <!-- /#addwizard --> </div>

                            <!-- /.panel.panel-default -->

                            <div class="panel panel-warning">

                                <div class="panel-heading">

                                    <h4 class="panel-title">

                                        <a href="#adjusthtml" data-parent="#accordion-demo" data-toggle="collapse">Ajustes SEO</a>

                                    </h4>

                                </div>

                                <div id="adjusthtml" class="panel-collapse collapse" style="height: 36.400001525878906px;">

                                    <div class="panel-body">

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="nombre_producto">Seo Titulo</label>

                                                <div class="col-md-9">

                                                    <input id="seo_titulo" name="seo_titulo" type="text" placeholder="Seo Titulo" class="form-control  {{ $errors->first('seo_titulo', 'has-error') }}" value="{!! old('Seo Titulo', $producto->seo_titulo) !!}">

                                                </div>

                                                {!! $errors->first('seo_titulo', '<span class="help-block">:message</span>') !!}

                                            </div> 

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">Seo Descripcion</label>

                                                <div class="col-md-9">

                                                    <input id="seo_descripcion" name="seo_descripcion" type="text" placeholder="Seo Descripcion" class="form-control   {{ $errors->first('seo_descripcion', 'has-error') }}" value="{!! old('Seo Titulo', $producto->seo_titulo) !!}">

                                                </div>

                                                 {!! $errors->first('seo_descripcion', '<span class="help-block">:message</span>') !!}

                                            </div>

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">Seo Url</label>

                                                <div class="col-md-9">

                                                    <input id="seo_url" name="seo_url" type="text" placeholder="Seo Url" class="form-control  {{ $errors->first('seo_url', 'has-error') }}" value="{!! old('Seo Url', $producto->seo_url) !!}">

                                                </div>

                                                 {!! $errors->first('seo_url', '<span class="help-block">:message</span>') !!}

                                            </div>

                                            <div class="acc-wizard-step"></div>

                                       
                                    </div>

                                    <!--/.panel-body --> </div>

                                <!-- /#adjusthtml --> </div>

                            <!-- /.panel.panel-default -->

                            <div class="panel panel-danger">

                                <div class="panel-heading">

                                    <h4 class="panel-title">

                                        <a href="#viewpage" data-parent="#accordion-demo" data-toggle="collapse">Caracteristicas Producto</a>

                                    </h4>

                                </div>

                                <div id="viewpage" class="panel-collapse collapse" style="height: 36.400001525878906px;">

                                    <div class="panel-body">                                   

                                            <div class="form-group">

                                                <label for="select21" class="col-md-3 control-label">
                                                    Categoria por Defecto
                                                </label>

                                                <div class="col-md-9"> 

                                                 <select id="id_categoria_default" name="id_categoria_default" class="form-control select2  {{ $errors->first('id_categoria_default', 'has-error') }} ">

                                                    <option value="">Select value...</option>

                                                        <option value="1" @if($producto->id_categoria_default === '1') selected="selected" @endif  >Primera</option>

                                                        <option value="2" @if($producto->id_categoria_default === '2') selected="selected" @endif >Segunda</option>

                                                        <option value="3" @if($producto->id_categoria_default === '3') selected="selected" @endif >Tercera</option>

                                                    </select>

                                                </div>

                                                {!! $errors->first('id_categoria_default', '<span class="help-block">:message</span>') !!}

                                               
                                            </div>

                                            <div class="form-group">

                                                <label for="select21" class="col-md-3 control-label">
                                                    Marca
                                                </label>

                                                <div class="col-md-9" >

                                                    <select id="id_marca" name="id_marca" class="form-control select2 {{ $errors->first('id_marca', 'has-error') }}  ">

                                                        <option value="">Select value...</option>
                                                        <option value="1" @if($producto->id_marca === '1') selected="selected" @endif >Primera</option>
                                                        <option value="2" @if($producto->id_marca === '2') selected="selected" @endif >Segunda</option>
                                                        <option value="3" @if($producto->id_marca === '3') selected="selected" @endif >Tercera</option>

                                                    </select>

                                                </div>

                                                {!! $errors->first('id_marca', '<span class="help-block">:message</span>') !!}
                                                
                                            </div>
                                           
                                            <div class="acc-wizard-step">
                                                
                                            </div>

                                            <button type="button" class="btn btn-danger finish">Finish</button>
                                        
                                    </div>

                                    <!--/.panel-body --> </div>

                                <!-- /#adjusthtml --> </div>

                            <!-- /.panel.panel-default --> </div>

                    </div>

                </div>

        {!! Form::close() !!}

        </div>

      </div>

    </div>

   </section>

 @stop

@section('footer_scripts')
    
    <script src="{{ asset('assets/vendors/acc-wizard/acc-wizard.min.js') }}" ></script>

    <script src="{{ asset('assets/js/pages/accordionformwizard.js') }}"  type="text/javascript"></script>

     <script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>

    <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


    <script src="{{ asset('assets/js/pages/addproductos.js') }}"></script>





    <script>

   
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });

        var FormDropzone = function() {
            return {
                //main function to initiate the module
                init: function() {
                    Dropzone.options.myDropzone = {
                        init: function() {
                            this.on("success", function(file,responseText) {
                                var obj = jQuery.parseJSON(responseText);
                                file.id = obj.id;
                                file.filename = obj.filename;
                                // Create the remove button
                                var removeButton = Dropzone.createElement("<button style='margin: 10px 0 0 15px;'>Remove file</button>");

                                // Capture the Dropzone instance as closure.
                                var _this = this;

                                // Listen to the click event
                                removeButton.addEventListener("click", function(e) {
                                    // Make sure the button click doesn't submit the form:
                                    e.preventDefault();
                                    e.stopPropagation();

                                    $.ajax({
                                        url: "file/delete",
                                        type: "DELETE",
                                        data: { "id" : file.id, "_token": '{{ csrf_token() }}' }
                                    });
                                    // Remove the file preview.
                                    _this.removeFile(file);
                                });

                                // Add the button to the file preview element.
                                file.previewElement.appendChild(removeButton);

                            });

                        }
                    }
                }
            };
        }();
        jQuery(document).ready(function() {

            FormDropzone.init();
        });
    </script>
    
@stop
