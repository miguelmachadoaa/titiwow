@extends('admin/layouts/default')

@section('title')
Precios Productos
@parent
@stop


@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Export Precios de Productos</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Escritorio
            </a>
        </li>
        <li>Export Precios Productos</li>
        <li class="active">Listado de Productos</li>
    </ol>
</section>

<section class="content paddingleft_right15">
    <div class="row">
     @include('flash::message')
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Export de Productos
                </h4>
                <div class="pull-right">
                    
                </div>
            </div>
            <br />



            
            <div class="panel-body table-responsive">

                <div style="margin-bottom: 1em; margin-top: 1em;" class="row">
                    
                        <form class="" method="post" action="{{secure_url('admin/reportes=/exportprecio')}}">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />


                <div class="form-group col-sm-3">
                    <label for="exampleInputName2">Producto</label>
                    <select class="form-control select2" name="producto" id="producto">

                        <option value="0">Todos</option>
                        @foreach($productos_list as $p)


                        <option value="{{$p->id}}">{{$p->nombre_producto.' - '.$p->presentacion_producto.' - '.$p->referencia_producto}}</option>

                        @endforeach
                    </select>
                </div>


                  <div class="form-group col-sm-3">
                    <label for="exampleInputName2">Rol</label>
                    <select class="form-control select2" name="rol" id="rol">
                          <option value="0">Todos</option>
                        @foreach($roles as $rol)

                        <option value="{{$rol->id}}">{{$rol->name}}</option>

                        @endforeach
                    </select>
                  </div>


                  <div class="form-group col-sm-3">
                    <label for="exampleInputEmail2">Departamento</label>
                  <select class="form-control" name="state" id="state">
                      <option value="0">Todos</option>
                        @foreach($states as $state)

                        <option value="{{$state->id}}">{{$state->state_name}}</option>

                        @endforeach
                    </select>
                  </div>


                  <div class="form-group col-sm-3">
                    <label for="exampleInputEmail2">Ciudad</label>
                  <select class="form-control select2" name="cities" id="cities">
                      <option value="0">Todos</option>
                       
                    </select>
                  </div>

                  <div class="clearfix"></div>

                <div class="col-sm-3">
                    <br>
                    <button type="submit" class="btn btn-default">Exportar</button>

                </div>

                  
                </form>


                </div>

                @if(count($precio_grupo))

                    @include('admin.productos.table_precio')

                 @endif
                 
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

