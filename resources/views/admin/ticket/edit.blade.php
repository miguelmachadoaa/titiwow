@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Editar Ticket :: @parent
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
    <h1> Editar Ticket</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                Inicio
            </a>
        </li>
        <li>
            <a href="#">Ticket</a>
        </li>
        <li class="active">Editar Ticket</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
            <!-- errors -->
            

            {!! Form::model($ticket, ['url' => secure_url('admin/ticket/'. $ticket->id), 'method' => 'put', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data', 'files'=> true]) !!}
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

                                    <option @if($ticket->departamento==$d->id) {{'Selected'}} @endif value="{{ $d->id }}">
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

                                                        <option @if($ticket->urgencia==$u->id) {{'Selected'}} @endif value="{{ $u->id }}">
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
                            first('titulo_ticket', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Asunto Ticket 
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="titulo_ticket" name="titulo_ticket" class="form-control" placeholder="Titulo ticket"
                                       value="{!! old('titulo_ticket', $ticket->titulo_ticket) !!}">
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
                                 <textarea class="textarea form-control" name="texto_ticket" id="texto_ticket" placeholder="Contenido Ticket" rows="5" cols="10">{{$ticket->texto_ticket}}</textarea>
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

                    @if($ticket->archivo!=null || $ticket->archivo!='')

                    <div class="col-sm-12">

                         <div class="col-sm-2">
                        
                    </div>

                    <div class="col-sm-5">
                        
                        <h3>Archivo :  <a href="{{secure_url('/uploads/ticket/'.$ticket->archivo)}}"></a></h3>

                    </div>
                        

                    </div>

                   

                    @endif
                   

                        <div class="form-group" style="margin-top: 1em;">
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
        $('.textarea').ckeditor({

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
