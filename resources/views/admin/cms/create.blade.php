@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Nueva Página :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ secure_asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}"  rel="stylesheet" media="screen"/>
    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>Crear Nueva Página</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                Inicio
            </a>
        </li>
        <li>
            <a href="#">Páginas</a>
        </li>
        <li class="active">Crear Página</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
            <!-- errors -->
            {!! Form::open(array('url' => secure_url('admin/cms'), 'method' => 'post', 'class' => 'bf')) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                 <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->first('titulo_pagina', 'has-error') }}">
                            {!! Form::text('titulo_pagina', null, array('class' => 'form-control input-md','placeholder'=> 'Titulo de la Página')) !!}
                            <span class="help-block">{{ $errors->first('titulo_pagina', ':message') }}</span>
                        </div>
                        <div class="box-body pad form-group {{ $errors->first('texto_pagina', 'has-error') }}">
                            <textarea class="textarea form-control" name="texto_pagina" id="texto_pagina" placeholder="Texto de la Página de Contenido" rows="5" cols="10"></textarea>
                            <span class="help-block">{{ $errors->first('texto_pagina', ':message') }}</span>
                        </div>
                        <div class="form-group {{ $errors->first('seo_titulo', 'has-error') }}">
                            {!! Form::text('seo_titulo', null, array('class' => 'form-control input-md','placeholder'=> 'Titulo Seo')) !!}
                            <span class="help-block">{{ $errors->first('seo_titulo', ':message') }}</span>
                        </div>
                        <div class="form-group {{ $errors->first('seo_descripcion', 'has-error') }}">
                            {!! Form::text('seo_descripcion', null, array('class' => 'form-control input-md','placeholder'=> 'Descripción SEO')) !!}
                            <span class="help-block">{{ $errors->first('seo_descripcion', ':message') }}</span>
                        </div>
                        <div class="form-group {{ $errors->first('slug', 'has-error') }}">
                            {!! Form::text('slug', null, array('class' => 'form-control input-md','placeholder'=> 'Slug')) !!}
                            <span class="help-block">{{ $errors->first('slug', ':message') }}</span>
                        </div>


                        <fieldset>
                            
                        <h3>Opciones robots.</h3>

                        <div class="checkbox">
                          <label>
                            <input type="checkbox" id="robots_all" name="robots_all" value="all" checked>
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
                            <input type="checkbox" id="robots_index" name="robots_index" value="index"    @if(in_array('index', $robots)) {{'checked'}} @endif >
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
                    

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Crear</button>
                            <a href="{{ route('admin.cms.index') }}"
                               class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                {!! Form::close() !!}
        </div>
    </div>
    <!--main content ends-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->

    <script  src="{{ secure_asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
    <script  src="{{ secure_asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
    <script  src="{{ secure_asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>
<script type="text/javascript" >
        // CKEditor Standard
        $('textarea#texto_pagina').ckeditor({
                height: '150px',
                toolbar: [{
                    name: 'document',
                        items: ['Source', '-', 'NewPage', 'Preview', '-', 'Templates']
                }, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
                    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'], // Defines toolbar group without name.
                        {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic']
                }
            ]
        });
    </script>

@stop
