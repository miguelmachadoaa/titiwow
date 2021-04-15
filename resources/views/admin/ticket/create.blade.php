@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Ticket :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
      <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>Crear Nuevo Ticket</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                Inicio
            </a>
        </li>
        <li>
            <a href="#">Ticket</a>
        </li>
        <li class="active">Crear Ticket</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
            <!-- errors -->
            

             <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{ secure_url('admin/ticket/create') }}">

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                 <div class="row">

                    <div class="col-sm-12" style="margin-top:1em;">

                         <div class="form-group {{ $errors->
                            first('departamento', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Departamento
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="departamento" name="departamento" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($departamentos as $d)

                                                        <option value="{{ $d->id }}">
                                                                {{ $d->nombre_departamento}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('departamento', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                    </div>

                    <div class="col-sm-12" style="margin-top:1em;">
                        
                        <div class="form-group {{ $errors->
                            first('urgencia', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Urgencia
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="urgencia" name="urgencia" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($urgencia as $u)

                                                        <option value="{{ $u->id }}">
                                                                {{ $u->nombre_urgencia}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('urgencia', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12" style="margin-top:1em;">
                        
                        <div class="form-group {{ $errors->
                            first('caso', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Casos
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="caso" name="caso" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($casos as $c)

                                                        <option value="{{ $c->id }}">
                                                                {{ $c->nombre_caso}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('caso', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>





                    <div class="col-sm-12" style="margin-top:1em;">
                        
                        <div class="form-group {{ $errors->
                            first('orden', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Orden del Ticket <small>Opcional</small>
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="orden" name="orden" class="form-control select2">

                                                        <option value="">Seleccione</option>
                                                        
                                                        @foreach($ordenes as $o)

                                                        <option value="{{ $o->id }}">
                                                                {{ $o->referencia.' - '.$o->first_name.' '.$o->last_name}}</option>
                                                        @endforeach
                                                        
                                                      
                                                    </select>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('orden', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>



                         

                    <div class="col-sm-12" style="margin-top:1em;">
                        

                        <div class="form-group {{ $errors->
                            first('titulo_ticket', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Asunto Ticket 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="titulo_ticket" name="titulo_ticket" class="form-control" placeholder="Titulo ticket"
                                       value="{!! old('titulo_ticket') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('titulo_ticket', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>



                    </div>


                        

                    <div class="col-sm-12" style="margin-top:1em;">
                        

                        <div class="form-group {{ $errors->
                            first('texto_ticket', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Contenido
                            </label>
                            <div class="col-sm-5">
                                 <textarea class="textarea form-control" name="texto_ticket" id="texto_ticket" placeholder="Contenido Ticket" rows="5" cols="10"></textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('texto_ticket', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-12" style="margin-top:1em; ">
                        

                        <div class="form-group {{ $errors->
                            first('archivo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Adjunto
                            </label>
                            <div class="col-sm-5">
                                  <input type="file" id="archivo" name="archivo">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('archivo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
                    </div>



                   

                      
                    

                        <div class="form-group col-sm-12" style="margin-top: 1em;">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-5">
                            <a href="{{ route('admin.ticket.index') }}"
                               class="btn btn-danger">Cancelar</a>
                            <button type="submit" class="btn btn-success">Crear</button></div>
                            
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
<script src="https://cdn.tiny.cloud/1/qc49iemrwi4gmrqtiuvymiviycjklawxnqmtcnvorw0hckoj/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script  src="{{ secure_asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
    <script  src="{{ secure_asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
    <script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ secure_asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>
<script type="text/javascript" >
 tinymce.init({
        selector:'#texto_ticket',
        width: '100%',
        height: 300
    });

 $('.select2').select2({
                placeholder: "Seleccionar",
                theme:"bootstrap"
            });
</script>

@stop
