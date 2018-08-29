
@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Nuevo Producto
@parent
@stop

{{-- page level styles --}}
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

{{-- Page content --}}
@section('content')

<section class="content-header">
                <!--section starts-->
                <h1>Nuevo Producto</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#">Productos</a>
                    </li>
                    <li class="active">Nuevo Producto</li>
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
                    Specials
                </h3>
                <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                             <i class="glyphicon glyphicon glyphicon-remove removepanel clickable"></i>
                        </span>
            </div>
            <div class="panel-body">
            {!! Form::open(['url' => 'admin\productos', 'class' => 'form-horizontal', 'id' => 'productosForm', 'name' => 'productosForm']) !!}

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
                                        <form id="form-divbasicos" class="form-horizontal">
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="nombre_producto">Nombre del Producto</label>
                                                <div class="col-md-9">
                                                    <input id="nombre_producto" name="nombre_producto" type="text" placeholder="Your name" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Your email" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia Sap</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto_sap" name="referencia_producto_sap" type="text" placeholder="Your email" class="form-control"></div>
                                            </div>
                                            <div class="acc-wizard-step"></div>
                                        </form>
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
                                        <form id="form-addwizard">
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label" for="descripcion_corta">Descripción Corta</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_corta" name="descripcion_corta" placeholder="Please enter your message here..." rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label" for="descripcion_larga">Descripción Larga</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_larga" name="descripcion_larga" placeholder="Please enter your message here..." rows="5"></textarea>
                                            </div>
                                        </div>

                                        <!-- Carga de imagenes -->
                                        <div class="row">   
                                         <div class="col-md-12" style="padding:30px;">
                                            {!! Form::open(array('url' => URL::to('admin/file/create'), 'method' => 'post', 'id'=>'myDropzone','class' => 'dropzone', 'files'=> true)) !!}
                                            <div class="fallback">
                                                <input name="file" type="file" multiple />
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                        </div>



                                            <div class="acc-wizard-step"></div>
                                        </form>
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
                                          <form id="form-divbasicos" class="form-horizontal">
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="nombre_producto">Seo Titulo</label>
                                                <div class="col-md-9">
                                                    <input id="seo_titulo" name="seo_titulo" type="text" placeholder="Seo Titulo" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Seo Descripcion</label>
                                                <div class="col-md-9">
                                                    <input id="seo_descripcion" name="seo_descripcion" type="text" placeholder="Seo Descripcion" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Seo Url</label>
                                                <div class="col-md-9">
                                                    <input id="seo_url" name="seo_url" type="text" placeholder="Seo Url" class="form-control"></div>
                                            </div>
                                            <div class="acc-wizard-step"></div>
                                        </form>
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
                                        <form id="form-viewpage">

                                            <div class="form-group">
                                                <label for="select21" class="col-md-3 control-label">
                                                    Categoria por Defecto
                                                </label>
                                                <div class="col-md-9">   
                                                 <select id="id_categoria_default" name="id_categoria_default" class="form-control select2">
                                                    <option value="">Select value...</option>
                                                        <option value="1">Primera</option>
                                                        <option value="2">Segunda</option>
                                                        <option value="3">Tercera</option>
                                                </select>
                                                </div>
                                               
                                            </div>

                                            <div class="form-group">
                                                <label for="select21" class="col-md-3 control-label">
                                                    Marca
                                                </label>
                                                <div class="col-md-9" >
                                                    <select id="id_marca" name="id_marca" class="form-control select2">
                                                        <option value="">Select value...</option>
                                                        <option value="1">Primera</option>
                                                        <option value="2">Segunda</option>
                                                        <option value="3">Tercera</option>
                                                    </select>
                                                </div>
                                            </div>
                        
                                           
                                            <div class="acc-wizard-step">
                                                
                                            </div>
                                            <li class="btn btn-danger finish" ><a href="javascript:;">Finish</a></li>
                                        </form>
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
    <!--main content ends--> </section>
            <!-- content --> 
    @stop

{{-- page level scripts --}}
@section('footer_scripts')
    
    <script src="{{ asset('assets/vendors/acc-wizard/acc-wizard.min.js') }}" ></script>
    <script src="{{ asset('assets/js/pages/accordionformwizard.js') }}"  type="text/javascript"></script>

     <script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>

    <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


    <script src="{{ asset('assets/js/pages/addproductos.js') }}"></script>





    <script>

    $('#rootwizard .finish').click(function () {
    var $validator = $('#commentForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("commentForm").submit();
    }

});
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });
$('#commentForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });




    $(document).ready(function(){
        $('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })
        


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
