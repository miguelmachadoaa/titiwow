@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Nueva Página :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
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
            {!! Form::open(array('url' => URL::to('admin/cms'), 'method' => 'post', 'class' => 'bf')) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                 <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->first('titulo_pagina', 'has-error') }}">
                            {!! Form::text('titulo_pagina', null, array('class' => 'form-control input-md','placeholder'=> 'Titulo de la Página')) !!}
                            <span class="help-block">{{ $errors->first('titulo_pagina', ':message') }}</span>
                        </div>
                        <div class="box-body pad form-group {{ $errors->first('texto_pagina', 'has-error') }}">
                            {!! Form::textarea('texto_pagina', NULL, array('placeholder'=>trans('blog/form.ph-content'),'rows'=>'5','class'=>'textarea form-control','style'=>'style="width: 100%; height: 200px !important; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"')) !!}
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
<script src="{{ asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

@stop
