@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Xml Productos
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

    <link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Xml Productos
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Xml Productos</li>
        <li class="active">Gestionar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Gestionar Xml Productos
                    </h4>
                </div>
                <div class="panel-body">

                    <div class="alert alert-success">
                        El precio de oferta es el que se mostrara en el xml 
                    </div>

                       <form enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ secure_url('admin/xml/') }}">
                            <!-- CSRF Token -->
                            <div class="form-group {{ $errors->
                                first('state_id', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Seleccione el Rol a Mostrar
                                </label>
                                <div class="col-sm-5">
                                    
                                    <select id="id_rol" name="id_rol" class="form-control select2">

                                        @foreach($roles as $rol)

                                        <option  @if($rol->id==$rolxml) {{'Selected'}} @endif    value="{{ $rol->id }}">
                                                {{ $rol->name}}</option>
                                        @endforeach
                                        
                                      
                                    </select>
                                </div>
                                
                            </div> 


                            <div class="form-group {{ $errors->
                                first('state_id', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Seleccione el producto 
                                </label>
                                <div class="col-sm-5">
                                    
                                    <select id="id_producto" name="id_producto" class="form-control select2">

                                        @foreach($listaproductos as $lp)

                                        <option      value="{{ $lp->id }}">
                                                {{ $lp->nombre_producto.' '.$lp->referencia_producto}}</option>
                                        @endforeach
                                        
                                      
                                    </select>
                                </div>
                                
                            </div> 

                            <div class="form-group col-sm-5  sm-offset-2">
                                
                                <button type="button" class="btn btn-primary addproducto">Agregar</button>
                            </div>




                          
                            <!-- CSRF Token -->
                            {{ csrf_field() }}


                    </form>



                           

                         @include('admin.xml.listaproductos')

                       <!--div class="form-group">
                            <div class="col-sm-4" style="margin-top: 2em;">
                                
                                <a class="btn btn-danger" href="{{ route('admin.almacenes.index') }}">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Actualizar
                                </button>
                            </div>
                        </div-->
                   
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

    <!-- row-->
</section>

@stop
@section('footer_scripts')

<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script type="text/javascript" src="{{ secure_asset('assets/js/in-view.min.js') }}"></script>



<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

<script>


$(".select2").select2();

    $('.addproducto').on('click', function(){

        base = $('#base').val();

        id_producto = $('#id_producto').val();

        id_rol = $('#id_rol').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_rol, id_producto, _token},
            url: base+"/admin/xml/addproducto",
                
            complete: function(datos){     

                $(".listaproductos").html(datos.responseText);

            }
        });

    });


    $('.listaproductos').on('click', '.delproducto', function(){

        base = $('#base').val();

        id = $(this).data('id');

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id, _token},
            url: base+"/admin/xml/delproducto",
                
            complete: function(datos){     

                $(".listaproductos").html(datos.responseText);

            }
        });

    });




    inView( 'figure2' ).on( 'enter', function( figure ) {
 
         var img = figure.querySelector( 'img' ); // 1
     
         if (  'undefined' !== typeof img.dataset.src ) { // 2
     
             //figure.classList.add( 'is-loading' ); // 3
     
             // 4
             newImg = new Image();
             img.src = img.dataset.src;
     
             img.addEventListener( 'load', function() {
     
                figure.innerHTML = ''; // 5
                figure.appendChild( this );

                // 6
               // setTimeout( function() {
               //    figure.classList.remove( 'is-loading' );
                //   figure.classList.add( 'is-loaded' );
                //}, 300 );
             } );
         }
    } );


    //$('#tableAlmacen').DataTable();

    $('.marcar').click(function(){


        $('.cb').each(function(){
            $(this).attr("checked", "checked");
        });
    });



    $('.desmarcar').click(function(){

        $('.cb').each(function(){
            $(this).removeAttr('checked');
        });
    });
    

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



</script>


@stop