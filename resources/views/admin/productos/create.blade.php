
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
    

    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

    <!-- stilos para la carga de imagen  -->
    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

    <!-- fin stilos para la carga de imagen  -->


<!-- stilos para arbol de categorias -->

    <link href="{{ asset('assets/vendors/jstree/css/style.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ asset('assets/vendors/treeview/css/bootstrap-treeview.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/treeview_jstree.css') }}" rel="stylesheet" type="text/css"/>

    <!-- fin stilos para arbol de categorias -->

    <!--end of page level css-->







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
                            Escritorio
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
            {!! Form::open(['url' => 'admin\productos', 'class' => 'form-horizontal', 'id' => 'productosForm', 'name' => 'productosForm', 'files'=> true]) !!}

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
                                        
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="nombre_producto">Nombre del Producto</label>
                                                <div class="col-md-9">
                                                    <input id="nombre_producto" name="nombre_producto" type="text" placeholder="Nombre del Producto" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Referencia del PRoducto" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia Sap</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto_sap" name="referencia_producto_sap" type="text" placeholder="Referencia Sap" class="form-control"></div>
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
                                                <textarea class="form-control resize_vertical" id="descripcion_corta" name="descripcion_corta" placeholder="Descripcion Corta" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label" for="descripcion_larga">Descripción Larga</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_larga" name="descripcion_larga" placeholder="Descripcion Larga" rows="5"></textarea>
                                            </div>
                                        </div>




                        <div class="form-group clearfix">

                            <label for="title" class="col-md-3 control-label">Imagen de Producto</label>


                            <div class="col-md-9">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}" alt="..."class="img-responsive"/>

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
                                                    <input id="seo_titulo" name="seo_titulo" type="text" placeholder="Seo Titulo" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Seo Descripcion</label>
                                                <div class="col-md-9">
                                                    <input id="seo_descripcion" name="seo_descripcion" type="text" placeholder="Seo Descripcion" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Slug</label>
                                                <div class="col-md-9">
                                                    <input id="slug" name="slug" type="text" placeholder="Seo Url" class="form-control"></div>
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
                                                 <select id="id_categoria_default" name="id_categoria_default" class="form-control select2">
                                                    <option value="">Seleccione</option>
                                                        
                                                         @foreach($categorias as $cat)
                                                        <option value="{{ $cat->id }}"
                                                                @if($cat->id == old('id_marca')) selected="selected" @endif >{{ $cat->nombre_categoria}}</option>
                                                        @endforeach
                                                </select>
                                                </div>
                                               
                                            </div>

                                            <div class="form-group">
                                                <label for="select21" class="col-md-3 control-label">
                                                    Marca
                                                </label>
                                                <div class="col-md-9" >
                                                    <select id="id_marca" name="id_marca" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                       
                                                        @foreach($marcas as $marca)
                                                        <option value="{{ $marca->id }}"
                                                                @if($marca->id == old('id_marca')) selected="selected" @endif >{{ $marca->nombre_marca}}</option>
                                                        @endforeach



                                                      
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Inventario Incial </label>
                                                <div class="col-md-9">
                                                    <input id="inventario_inicial" name="inventario_inicial" type="number" placeholder="Inventario Inicial" class="form-control"></div>
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
    <!--main content ends--> </section>
            <!-- content --> 
    @stop

{{-- page level scripts --}}
@section('footer_scripts')

    <!-- js para la carga de imahenes  -->
        <script src="{{ asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

    <!-- fin  js para la carga de imahenes  -->

    
    <script src="{{ asset('assets/vendors/acc-wizard/acc-wizard.min.js') }}" ></script>
    <script src="{{ asset('assets/js/pages/accordionformwizard.js') }}"  type="text/javascript"></script>

    
    <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


   


     <!-- Arbol de categorias -->
    <script src="{{ asset('assets/vendors/jstree/js/jstree.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/treeview/js/bootstrap-treeview.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>
   <!-- <script src="{{ asset('assets/js/pages/treeview_jstree.js') }}" type="text/javascript"></script>-->

    <script type="text/javascript">
        

          function verificarCategorias (){

              $(".node-checked").each(function(){
                    alert($(this).text())
                });
              
        }

        $(document).ready(function(){

        defaultData=$("#tree").val();

        var $checkableTree = $('#treeview-checkable').treeview({
            data: defaultData,
            showIcon: false,
            showCheckbox: true,
            onNodeChecked: function(event, node) {
                $('#checkable-output').prepend('<p>' + node.text + ' was checked</p>');


                $(".node-checked").each(function(){
                    alert($(this).text())
                });

            },
            onNodeUnchecked: function(event, node) {
                $('#checkable-output').prepend('<p>' + node.text + ' was unchecked</p>');

              $(".node-checked").each(function(){
                    alert($(this).text())
                });

               

            }
        });

        });

    </script>


    <script type="text/javascript">
        
        

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
                }
            },
            required: true,
            minlength: 3
        },
        referencia_producto_sap: {
            validators: {
                notEmpty: {
                    message: 'The referencia_producto_sap name is required'
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
        $('.select2').select2({
            placeholder: "select",
            theme:"bootstrap"
        });
    })

    </script>

    <!--Fin  Arbol de categorias -->

@stop
