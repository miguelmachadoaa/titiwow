@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Pedidos
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop



{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Pedidos</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Pedidos </a></li>
        <li class="active">Listado</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Pedidos
                    </h4>
                    
                </div>
                <br />
                <div class="panel-body">
                     <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                     <div class="row">
                         
                         <div class="col-sm-8">

                            <div class="row">
                                <div class="col-sm-4">

                                   <h4>Categorias</h4> 

                                   <select class="form-control" name="categoria" id="categoria">

                                        @foreach($categorias as $c)

                                            <option value="{{$c->id}}">{{$c->nombre_categoria}}</option>
                                           
                                        @endforeach

                                    </select>
                                </div>


                                <div class="col-sm-4">

                                   <h4>Marcas</h4> 

                                   <select class="form-control" name="marca" id="marca">

                                        @foreach($marcas as $m)

                                            <option value="{{$m->id}}">{{$m->nombre_marca}}</option>
                                            
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-sm-4">

                                   <h4>Buscar</h4> 

                                   
                                   <div class="input-group">
                                     
                                      <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar...">

                                       <span class="input-group-btn">
                                        <button class="btn btn-default btn_buscar" type="button">Buscar!</button>
                                      </span>
                                    </div><!-- /input-group -->


                                </div>



                            </div>

                            <div class="row lista_de_productos" style="margin-right: 1em;">
                                
                               @include('admin.pedidos.table')
                                
                            </div>
                             Panel de Venta
                         </div>
                         <div class="col-sm-4 listaorden">
                         <h3>Detalle de Compra</h3>

                         @include('admin.pedidos.listaorden')
                        </div>
                     </div> 

                    
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">


@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<div class="modal fade" id="users_exists" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                @lang('groups/message.users_exists')
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>


<script>

    $('#categoria').on('change', function(){

             base=$('#base').val();

             categoria=$('#categoria').val();

            $.get(base+'/admin/pedidos/'+categoria+'/datacategorias', function(data) {

                    $('.lista_de_productos').html(data);
            });
    });


      $('#marca').on('change', function(){

             base=$('#base').val();

             marca=$('#marca').val();

            $.get(base+'/admin/pedidos/'+marca+'/datamarcas', function(data) {

                    $('.lista_de_productos').html(data);
            });
    });


      $('.btn_buscar').on('click', function(){

             base=$('#base').val();

             buscar=$('#buscar').val();

            $.get(base+'/admin/pedidos/'+buscar+'/databuscar', function(data) {

                    $('.lista_de_productos').html(data);
            });
    });


      $('.addproducto').on('click', function(){

             base=$('#base').val();

             id=$(this).data('id');

            $.get(base+'/admin/pedidos/'+id+'/addtocart', function(data) {

                    $('.listaorden').html(data);
            });
    });













</script>
@stop
