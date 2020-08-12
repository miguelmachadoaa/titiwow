@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Configuracion Seo
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Configuracion  Seo
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
                       Editar Configuracion  Seo
                    </h4>
                </div>
                <div class="panel-body">

         <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                    
                    
                        {!! Form::model($configuracion, ['url' => secure_url('admin/configuracion/'. $configuracion->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          



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
                                        H1 Categorias
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
                                        H1 Terminos
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
                                                        <input type="checkbox" id="robots_index" name="robots_index" value="index"    @if(in_array('index', $robots)) {{'checked'}} @endif >
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
