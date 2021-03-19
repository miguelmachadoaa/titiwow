@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Destacados
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
        Destacados
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Destacados</li>
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
                       Gestionar Destacados
                    </h4>
                </div>
                <div class="panel-body">

                   

                       <form enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ secure_url('admin/xml/') }}">
                            <!-- CSRF Token -->
                            <div class="form-group {{ $errors->
                                first('state_id', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Seleccione el grupo
                                </label>
                                <div class="col-sm-5">
                                    
                                    <select id="id_grupo" name="id_grupo" class="form-control select2">

                                        <option      value="1"> Index</option>
                                        <option      value="2"> Qlub</option>
                                        
                                      
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

                                        @foreach($productos as $lp)

                                        <option      value="{{ $lp->id }}">
                                                {{ $lp->nombre_producto.' '.$lp->referencia_producto}}

                                        </option>
                                        @endforeach
                                        
                                      
                                    </select>
                                </div>
                                
                            </div> 

                            <div class="form-group col-sm-5  sm-offset-2">
                                
                                <button type="button" class="btn btn-primary addproductodestacado">Agregar</button>

                            </div>

                            {{ csrf_field() }}

                    </form>


                         @include('admin.productos.listaproductos')

                      
                   
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

    $(document).ready(function(){



    base=$('#base').val();

    var table =$('#tableAlmacen').DataTable({
        "processing": true,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": base+'/admin/productos/datadestacados/list'
        }
    });

    table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    });


$(".select2").select2();

    $('.addproductodestacado').on('click', function(){

        base = $('#base').val();

        id_producto = $('#id_producto').val();

        id_grupo = $('#id_grupo').val();

        _token = $('#_token').val();


         $.ajax({
            type: "POST",
            data:{ id_grupo, id_producto, _token},
            url: base+"/admin/productos/addproductodestacado",
                
            complete: function(datos){     

                table.ajax.reload();

                //$(".listaproductos").html(datos.responseText);

            }
        });

    });


        $('.listaproductos').on('click', '.eliminarproductodestacado', function(){

            base = $('#base').val();

            id = $(this).data('id');

            _token = $('#_token').val();

             $.ajax({
                type: "POST",
                data:{ id, _token},
                url: base+"/admin/productos/eliminarproductodestacado",
                    
                complete: function(datos){   

                table.ajax.reload();  

                   // $(".listaproductos").html(datos.responseText);

                }
            });

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