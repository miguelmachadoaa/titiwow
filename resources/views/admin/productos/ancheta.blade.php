@extends('admin/layouts/default')

@section('title')
Configuracion Ancheta
@parent
@stop


@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Configuracion Ancheta</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Escritorio
            </a>
        </li>
        <li>Configuracion Ancheta</li>
        <li class="active">Listado de Productos</li>
    </ol>
</section>

<section class="content paddingleft_right15">
    <div class="row">
     @include('flash::message')
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Configuracion Ancheta
                </h4>
                <div class="pull-right">
                    
                </div>
            </div>
            <br />
            
            <div class="panel-body table-responsive">

                <div style="margin-bottom: 1em; margin-top: 1em;" class="row">


                     <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{{ secure_url('admin/productos/'.$producto->id.'/storecategoria') }}">
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                             <input type="hidden" name="id_ancheta" id="id_ancheta" value="{{$producto->id}}">
                          
                             <div class="form-group {{ $errors->
                            first('nombre_categoria', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Nombre Categoria
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control" placeholder="Nombre de Categoria"
                                           value="">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('nombre_categoria', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>



                            <div class="form-group {{ $errors->
                            first('cantidad_minima', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Cantidad minima a seleccionar
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" step="1" min="1" id="cantidad_minima" name="cantidad_minima" class="form-control" placeholder="Cantidad minima a seleccionar"
                                           value="">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('cantidad_minima', '<span class="help-block">:message</span> ') !!}
                                </div>
                            </div>





                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ secure_url('admin/productos/'.$producto->id.'edit') }}">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">Crear</button>
                                
                            </div>
                        </div>
                    </form>



















                </div>
                 
            </div>
        </div>
 </div>

 <div class="row">
     


     <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Categorias 
                </h4>
                <div class="pull-right">
                    
                </div>
            </div>
            <br />
            
            <div class="panel-body table-responsive">

                <div style="margin-bottom: 1em; margin-top: 1em;" class="row">






                    @if(count($categorias ))

                    <table class="table">
                        <tr>
                            
                            <th>Id</th>
                            <th>Categoria</th>
                            <th>Cantidad de Productos</th>
                            <th>Accion</th>
                        </tr>


                        @foreach($categorias as $c )

                        <tr>
                            <td>{{$c->id}}</td>
                            <td>
                                {{$c->nombre_categoria}}
                            </td>

                            <td>{{count($c->productos)}}</td>
                            <td>


                                


                                <a  href="{{secure_url('admin/productos/'.$c->id.'/gestionarancheta')}}" class="btn btn-success">Gestionar</i></a>

                                <a  href="{{secure_url('admin/productos/'.$c->id.'/eliminarcategoria')}}" class="btn btn-danger"><i class=" fa fa-trash"></i></a>

                                <a  href="{{secure_url('admin/productos/'.$c->id.'/editarcategoria')}}" class="btn btn-info"><i class=" fa fa-pencil"></i></a>

                            </td>
                        </tr>


                        @endforeach  
                        

                    </table>

                    @else

                    @endif 





                </div>
                 
            </div>
        </div>



 </div>
</section>

<input type="hidden" name="base" id="base" value="{{secure_url('/')}}">
@stop


@section('footer_scripts')

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>




<script>


     $(document).ready(function(){

    $('.select2').select2();
        

       
        //Inicio select región
                

            //inicio select ciudad
            $('select[name="state"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="cities"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="cities"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="cities"]').empty();
                    }
                });
            //fin select ciudad
        });

     </script>


    @stop

