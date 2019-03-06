@extends('admin/layouts/default')

@section('title')
@lang('productos/title.edit') :: @parent
@parent
@stop

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





    <!-- fin stilos para la carga de imagen  -->
    
@stop


@section('content')
  @include('core-templates::common.errors')
    <section class="content-header">
     <h1>@lang('productos/title.edit')</h1>
     <ol class="breadcrumb">
         <li>
             <a href="{{ secure_url('admin') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
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


             @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
       

        {!! Form::model($producto, ['url' => secure_url('admin/productos/'. $producto->id.'/relacionados'), 'method' => 'put', 'id'=>'productosForm', 'files'=> true]) !!}

      
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
         <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
         <input type="hidden" name="id_producto" id="id_producto" value="{{ $producto->id }}">

               <div class="row">


                        <div class="form-group col-sm-12 {{ $errors->
                            first('id_impuesto', 'has-error') }}">
                            <label for="select21" class="col-md-3 control-label">
                                Seleccione el producto
                            </label>
                            <div class="col-sm-6">   

                                <select id="id_relacionado" name="id_relacionado" class="form-control select21">
                                    <option value="">Seleccione</option>
                                        
                                        @foreach($productos as $pro)
                                            <option value="{{ $pro->id }}">
                                                {{ $pro->nombre_producto}}</option>
                                        @endforeach
                                </select>

                             
                            </div>
                            <div class="col-sm-3">
                                <button type="button"  class="btn btn-primary addRelacionado">Agregar</button>
                            </div>
                           
                        </div>
                       


               </div>


               <div class="row tabla_relacionado">

                  @include('admin.productos.tabla_relacionados')

               </div>

        {!! Form::close() !!}

        <a class="btn btn-default" href="{{ secure_url('/admin/productos') }}">@lang('button.back')</a>

        </div>

      </div>

    </div>

   </section>

 @stop

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

        base=$('#base').val();

        $('.addRelacionado').on('click', function(){

            id_producto=$('#id_producto').val();

            id_relacionado=$('#id_relacionado').val();

            if (id_producto==null || id_producto=='' || id_relacionado==null || id_relacionado=='' || id_relacionado==undefined || id_producto==undefined) {}else{



                 $.ajax({
                    type: "POST",
                    data:{  id_producto, id_relacionado },

                    url: base+"/admin/productos/addrelacionado",
                        
                    complete: function(datos){     

                           // data=JSON.parse(datos.responseText);

                           // localStorage.setItem('ubicacion', datos.responseText);

                            $('.tabla_relacionado').html(datos);

                            

                    }
                });

            }



        });

         $('.select21').select2({
            placeholder: "select",
            theme:"bootstrap"
        });


    });

     
    



   </script>


    <!--Fin  Arbol de categorias -->
    
@stop








