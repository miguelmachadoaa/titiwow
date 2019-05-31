@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Cupon
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

<link href="{{ secure_asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Cupon
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Cupons</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Configurar Cupon  Categorias y productos
                    </h4> 
                </div>
                <div class="panel-body">

                    <input type="hidden" name="id_cupon" id="id_cupon" value="{{ $cupon->id }}">

                        
                    <fieldset>
                        <legend>Filtro para Categorias</legend>

                        <div class="row">
                            
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_categoria', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Categoria de Productos 
                                </label>
                                <div class="col-sm-8">   
                                 <select id="id_categoria" name="id_categoria" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($categorias as $categoria)

                                        <option value="{{$categoria->id}}">{{$categoria->nombre_categoria}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_categoria', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" role="button" class="btn btn-success addCategoriaCupon" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listcategorias">

                            @include('frontend.cupones.listcategorias')
                            


                        </div>
                    </fieldset>

                        <hr>

                     <fieldset>
                        <legend>Filtro para Marcas</legend>

                        <div class="row">
                            
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_marca', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Marcas de Productos 
                                </label>
                                <div class="col-sm-8">   
                                 <select id="id_marca" name="id_marca" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($marcas as $marca)

                                        <option value="{{$marca->id}}">{{$marca->nombre_marca}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_marca', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" role="button" class="btn btn-success addMarcaCupon" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listmarcas">

                            @include('frontend.cupones.listmarcas')
                            


                        </div>
                    </fieldset>


                   

                    <hr>

                    <fieldset>
                        <legend>Filtro para Productos</legend>

                        <div class="row">
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_producto', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Producto
                                </label>
                                <div class="col-sm-8">   

                                 <select id="id_producto" name="id_producto" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($productos as $producto)

                                        <option value="{{$producto->id}}">{{$producto->nombre_producto.' - '.$producto->referencia_producto}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_producto', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>

                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" class="btn btn-success addProducto" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listproductos">

                          @include('frontend.cupones.listproductoss')


                        </div>
                    </fieldset>


                   
                      
                      
                   
                </div>
            </div>













            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Configurar Cupon  Empresas, roles y clientes
                    </h4>
                </div>
                <div class="panel-body">

                    <input type="hidden" name="id_cupon" id="id_cupon" value="{{ $cupon->id }}">

                        
                    <fieldset>
                        <legend>Filtro para Empresas</legend>

                        <div class="row">
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_empresa', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Empresas
                                </label>
                                <div class="col-sm-8">   

                                 <select id="id_empresa" name="id_empresa" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($empresas as $empresa)

                                        <option value="{{$empresa->id}}">{{$empresa->nombre_empresa}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_empresa', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>

                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" class="btn btn-success addEmpresaCupon" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listempresas">

                          @include('frontend.cupones.listempresas')


                        </div>
                    </fieldset>


                     <hr>

                    <fieldset>
                        <legend>Filtro para Clientes</legend>

                        <div class="row">
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_cliente', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Cliente
                                </label>
                                <div class="col-sm-8">   

                                 <select id="id_cliente" name="id_cliente" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($clientes as $cliente)

                                        <option value="{{$cliente->id_user_client}}">{{$cliente->first_name.' '.$cliente->last_name.' - '.$cliente->doc_cliente}}</option>
                                    @endforeach

                                </select>

                                  {!! $errors->first('id_cliente', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>

                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" class="btn btn-success addCliente" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listclientes">

                          @include('frontend.cupones.listclientes')


                        </div>
                    </fieldset>

                     <hr>

                    <fieldset>
                        <legend>Filtro para Roles</legend>

                        <div class="row">
                                
                             <div class="form-group col-sm-8 {{ $errors->first('id_rol', 'has-error') }}">
                                <label for="select21" class="col-sm-4 control-label">
                                  Rol
                                </label>
                                <div class="col-sm-8">   

                                 <select id="id_rol" name="id_rol" class="form-control select2">
                                    
                                    <option value="">Seleccione</option>
                                    @foreach($roles as $rol)

                                        <option value="{{$rol->id}}">{{$rol->name}}</option>

                                    @endforeach

                                </select>

                                  {!! $errors->first('id_rol', '<span class="help-block">:message</span> ') !!}
                                </div>
                           
                            </div>

                            <div class="form-group col-sm-4">
                                <div class="col-sm-offset-2 col-sm-4">
                                    

                                    <button type="button" class="btn btn-success addRol" >
                                        Agregar
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row listroles">

                          @include('frontend.cupones.listroles')


                        </div>
                    </fieldset>
                      
                      
                   
                </div>
            </div>





        </div>
    </div>

    <input type="hidden" value="{{secure_url('/')}}" id="base" name="base">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <!-- row-->
</section>

@stop


@section('footer_scripts')

<script src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script >
    
    $('.addCategoriaCupon').on('click', function(){

        base = $('#base').val();

        id_categoria = $('#id_categoria').val();

        id_cupon = $('#id_cupon').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_categoria, id_cupon, _token},
            url: base+"/admin/cupones/"+id_categoria+"/addcategoria",
                
            complete: function(datos){     

                $(".listcategorias").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delcuponcategoria',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_cupon = $(this).data('idcupon');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_cupon, _token},
            url: base+"/admin/cupones/"+id+"/delcategoria",
                
            complete: function(datos){     

                $(".listcategorias").html(datos.responseText);

            }
        });

    });


     $('.addMarcaCupon').on('click', function(){

        base = $('#base').val();

        id_marca = $('#id_marca').val();

        id_cupon = $('#id_cupon').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_marca, id_cupon, _token},
            url: base+"/admin/cupones/"+id_marca+"/addmarca",
                
            complete: function(datos){     

                $(".listmarcas").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delcuponmarca',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_cupon = $(this).data('idcupon');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_cupon, _token},
            url: base+"/admin/cupones/"+id+"/delmarca",
                
            complete: function(datos){     

                $(".listmarcas").html(datos.responseText);

            }
        });

    });


     $('.addEmpresaCupon').on('click', function(){

        base = $('#base').val();

        id_empresa = $('#id_empresa').val();

        id_cupon = $('#id_cupon').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_empresa, id_cupon, _token},
            url: base+"/admin/cupones/"+id_empresa+"/addempresa",
                
            complete: function(datos){     

                $(".listempresas").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delcuponempresa',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_cupon = $(this).data('idcupon');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_cupon, _token},
            url: base+"/admin/cupones/"+id+"/delempresa",
                
            complete: function(datos){     

                $(".listempresas").html(datos.responseText);

            }
        });

    });


   
        
        


        $('.addProducto').on('click', function(){

        base = $('#base').val();

        id_producto = $('#id_producto').val();

        id_cupon = $('#id_cupon').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_producto, id_cupon, _token},
            url: base+"/admin/cupones/"+id_producto+"/addproducto",
                
            complete: function(datos){     

                $(".listproductos").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delcuponproducto',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_cupon = $(this).data('idcupon');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_cupon, _token},
            url: base+"/admin/cupones/"+id+"/delproducto",
                
            complete: function(datos){     

                $(".listproductos").html(datos.responseText);

            }
        });

    });


      $('.addCliente').on('click', function(){

        base = $('#base').val();

        id_cliente = $('#id_cliente').val();

        id_cupon = $('#id_cupon').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_cliente, id_cupon, _token},
            url: base+"/admin/cupones/"+id_producto+"/addcliente",
                
            complete: function(datos){     

                $(".listclientes").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delcuponcliente',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_cupon = $(this).data('idcupon');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_cupon, _token},
            url: base+"/admin/cupones/"+id+"/delcliente",
                
            complete: function(datos){     

                $(".listclientes").html(datos.responseText);

            }
        });

    });


       $('.addRol').on('click', function(){

        base = $('#base').val();

        id_rol = $('#id_rol').val();

        id_cupon = $('#id_cupon').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_rol, id_cupon, _token},
            url: base+"/admin/cupones/"+id_rol+"/addrol",
                
            complete: function(datos){     

                $(".listroles").html(datos.responseText);

            }
        });

    });

    $(document).on('click','.delcuponrol',  function(){

        base = $('#base').val();

        id = $(this).data('id');

        id_cupon = $(this).data('idcupon');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, id_cupon, _token},
            url: base+"/admin/cupones/"+id+"/delrol",
                
            complete: function(datos){     

                $(".listroles").html(datos.responseText);

            }
        });

    });







 $(document).ready(function(){
        //Inicio select región

        $('.select2').select2({
            placeholder: "Seleccionar",
            theme:"bootstrap"
        });
                        
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
