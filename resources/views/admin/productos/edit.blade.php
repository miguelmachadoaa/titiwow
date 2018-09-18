@extends('admin/layouts/default')

@section('title')
@lang('productos/title.edit') :: @parent
@parent
@stop

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





    <!-- fin stilos para la carga de imagen  -->
    
@stop


@section('content')
  @include('core-templates::common.errors')
    <section class="content-header">
     <h1>@lang('productos/title.edit')</h1>
     <ol class="breadcrumb">
         <li>
             <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                 @lang('general.dashboard')
             </a>
         </li>
         <li>@lang('productos/title.title')</li>
         <li class="active">@lang('productos/title.edit') </li>
     </ol>
    </section>
    <section class="content paddingleft_right15">
      <div class="row">
      <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('productos/title.edit')
                </h4></div>
            <br />
        <div class="panel-body">
       

        {!! Form::model($producto, ['url' => URL::to('admin/productos/'. $producto->id.''), 'method' => 'put', 'id'=>'productosForm', 'files'=> true]) !!}

      
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

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

                                        <a href="#divbasicos" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.basic')</a>

                                    </h4>

                                </div>

                                <div id="divbasicos" class="panel-collapse collapse in">

                                    <div class="panel-body">

                                            <div class="form-group clearfix {{ $errors->first('nombre_producto', 'has-error') }} ">

                                                <label class="col-md-3 control-label" for="nombre_producto">@lang('productos/title.name')</label>

                                                <div class="col-md-9">

                                                    <input id="nombre_producto" name="nombre_producto" type="text" placeholder="Nombre del producto" class="form-control  {{ $errors->first('nombre_producto', 'has-error') }}" value="{!! old('nombre_producto', $producto->nombre_producto) !!}">
                                                </div>
                                                 {!! $errors->first('nombre_producto', '<span class="help-block">:message</span>') !!}

                                            </div>

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.reference')</label>

                                                <div class="col-md-9">

                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Referencia" class="form-control  {{ $errors->first('referencia_producto', 'has-error') }}" value="{!! old('Referencia Sap', $producto->referencia_producto_sap) !!}" value="{!! old('referencia', $producto->referencia_producto) !!}" >
                                                </div>
                                                 {!! $errors->first('referencia_producto', '<span class="help-block">:message</span>') !!}
                                            </div>

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.reference_sap')</label>

                                                <div class="col-md-9">

                                                    <input id="referencia_producto_sap" name="referencia_producto_sap" type="text" placeholder="Referencia Sap" class="form-control  {{ $errors->first('referencia_producto_sap', 'has-error') }}" value="{!! old('Referencia Sap', $producto->referencia_producto_sap) !!}">

                                                </div>

                                                {!! $errors->first('referencia_producto_sap', '<span class="help-block">:message</span>') !!}
                                            </div>

                                            <div class="acc-wizard-step"></div>

                                        <a class="btn btn-default" href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.next')</a>


                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#prerequisites --> </div>
                            <!-- /.panel.panel-default -->

                            <div class="panel panel-info">

                                <div class="panel-heading">

                                    <h4 class="panel-title">

                                        <a href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">@lang('productos/title.description')</a>

                                    </h4>

                                </div>

                                <div id="addwizard" class="panel-collapse collapse awd-h" style="height: 36.400001525878906px;">

                                    <div class="panel-body">

                                        <div class="form-group clearfix">

                                            <label class="col-md-3 control-label" for="descripcion_corta">@lang('productos/title.description_sort')</label>

                                            <div class="col-md-9">

                                                <textarea class="form-control col-sm-12 {{ $errors->first('descripcion_corta', 'has-error') }}  resize_vertical" id="descripcion_corta" name="descripcion_corta" placeholder="Descripcion Corta" rows="5">{!! old('Descripción Corta', $producto->descripcion_corta) !!}</textarea>

                                            </div>

                                            {!! $errors->first('descripcion_corta', '<span class="help-block">:message</span>') !!}

                                        </div>

                                        <div class="form-group clearfix">

                                            <label class="col-md-3 control-label" for="descripcion_larga">@lang('productos/title.description_large')</label>

                                            <div class="col-md-9">

                                                <textarea class="form-control col-sm-12  {{ $errors->first('descripcion_larga', 'has-error') }} resize_vertical" id="descripcion_larga" name="descripcion_larga" placeholder="Descripcion Larga" rows="5">{!! old('Descripción Larga', $producto->descripcion_larga) !!}</textarea>

                                            </div>

                                            {!! $errors->first('descripcion_larga', '<span class="help-block">:message</span>') !!}

                                        </div>

                                        <!-- Carga de imagenes -->


                        <div class="form-group clearfix">

                            <label for="title" class="col-md-3 control-label">Imagen de Producto</label>


                            <div class="col-md-9">

                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;">

                                    @if($producto->imagen_producto!='0')

                                        <img src="{{URL::to('uploads/productos/'.$producto->imagen_producto)}}" class="img-responsive" alt="Image">

                                    @else
                                        
                                        <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}" alt="..."
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

                                        <input type="file" name="image" id="pic" accept="image/*"/>

                                    </span>
                                   
                                    <span class="btn btn-primary fileinput-exists"
                                          data-dismiss="fileinput">Eliminar</span>

                                </div>

                            </div>
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

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="nombre_producto">@lang('productos/title.seo_title')</label>

                                                <div class="col-md-9">

                                                    <input id="seo_titulo" name="seo_titulo" type="text" placeholder="Seo Titulo" class="form-control  {{ $errors->first('seo_titulo', 'has-error') }}" value="{!! old('Seo Titulo', $producto->seo_titulo) !!}">

                                                </div>

                                                {!! $errors->first('seo_titulo', '<span class="help-block">:message</span>') !!}

                                            </div> 

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.seo_des')</label>

                                                <div class="col-md-9">

                                                    <input id="seo_descripcion" name="seo_descripcion" type="text" placeholder="Seo Descripcion" class="form-control   {{ $errors->first('seo_descripcion', 'has-error') }}" value="{!! old('Seo Titulo', $producto->seo_titulo) !!}">

                                                </div>

                                                 {!! $errors->first('seo_descripcion', '<span class="help-block">:message</span>') !!}

                                            </div>

                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.slug')</label>

                                                <div class="col-md-9">

                                                    <input id="slug" name="slug" type="text" placeholder="Seo Url" class="form-control  {{ $errors->first('seo_url', 'has-error') }}" value="{!! old('Seo Url', $producto->slug) !!}">

                                                </div>

                                                 {!! $errors->first('slug', '<span class="help-block">:message</span>') !!}

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
                                            <label>@lang('productos/title.tree')</label>
                                            <div id="treeview-checkable" class=""></div>

                                            <!-- Botones anteriro y siguiente manuales -->
                                            
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

                                            <div class="form-group">

                                                <label for="select21" class="col-md-3 control-label">
                                                    @lang('productos/title.category_default')
                                                </label>

                                                <div class="col-md-9"> 

                                                 <select id="id_categoria_default" name="id_categoria_default" class="form-control   {{ $errors->first('id_categoria_default', 'has-error') }} ">

                                                    <option value="">Seleccione</option>

                                                         @foreach($categorias as $cat)
                                                        <option value="{{ $cat->id }}"
                                                                @if($cat->id == $producto->id_categoria_default)) selected="selected" @endif >{{ $cat->nombre_categoria}}</option>
                                                        @endforeach
                                                </select>

                                                    </select>

                                                </div>

                                                {!! $errors->first('id_categoria_default', '<span class="help-block">:message</span>') !!}

                                               
                                            </div>

                                                <br>    


                                            <div class="form-group">

                                                <label for="select21" class="col-md-3 control-label">
                                                    @lang('productos/title.marca')
                                                </label>

                                                <div class="col-md-9" >

                                                    <select id="id_marca" name="id_marca" class="form-control  {{ $errors->first('id_marca', 'has-error') }}  ">

                                                        <option value="">Seleccione</option>
                                                         @foreach($marcas as $marca)
                                                        <option value="{{ $marca->id }}"
                                                                @if($marca->id == $producto->id_marca) selected="selected" @endif >{{ $marca->nombre_marca}}</option>
                                                        @endforeach

                                                    </select>

                                                </div>

                                                {!! $errors->first('id_marca', '<span class="help-block">:message</span>') !!}
                                                
                                            </div>

                                                <br>    


                                            <div class="form-group clearfix">

                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.inventary') </label>

                                                <div class="col-md-9">
                                                    <input id="inventario_inicial" name="inventario_inicial" type="number" placeholder="Inventario Inicial" class="form-control" value="{{$producto->inventario_inicial}}" readonly="true" >

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
                                        
                                            <div class="form-group clearfix col-sm-12">
                                                <label class="col-md-3 control-label" for="referencia_producto">@lang('productos/title.price') </label>
                                                <div class="col-md-9">
                                                    <input id="precio_base" step="0.01" name="precio_base" type="number" placeholder="Precio" class="form-control" value="{{$producto->precio_base}}" onblur="setprecio();" ></div>
                                            </div>

                                            <!--  precios por roles-->

                                            @foreach($roles as $rol)

                                                <div class="form-group clearfix col-sm-12">
                                                <label class="col-md-3 control-label" for="referencia_producto">Precio para {{ $rol->name }} </label>
                                                <div class="col-md-9">
                                                    <input id="rolprecio_{{ $rol->id }}" step="0.01" name="rolprecio_{{ $rol->id }}" type="number" placeholder="Precio" class="form-control rolprecio" value="{{$rol->precio}}" ></div>
                                            </div>
                                                        
                                            @endforeach



                                            <!--  Botons anterior sguiente-->
                        
                                           
                                            <div class="acc-wizard-step">
                                                
                                            </div>

                                            <a class="btn btn-default" href="#viewpage" data-parent="#accordion-demo" data-toggle="collapse">@lang('button.previous')</a>
                                            
                                            <button type="button" class="btn btn-danger finish">@lang('productos/title.enviar') </button>
                                        
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#adjusthtml --> </div>

                            <!-- /.panel.panel-default --> </div>




                    </div>

                </div>

        {!! Form::close() !!}

        <a class="btn btn-default" href="{{ url('/admin/productos') }}">@lang('button.back')</a>

        </div>

      </div>

    </div>

   </section>

 @stop

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
        $('.select2').select2({
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

    </script>

    <!--Fin  Arbol de categorias -->
    
@stop








