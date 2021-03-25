@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Almacen
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
        Gestionar Almacen
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Almacens</li>
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
                       Gestionar Almacen
                    </h4>
                </div>
                <div class="panel-body">

                    <div class="row">

                        <input type="hidden" id="id_almacen" name="id_almacen" value="{{$almacen->id}}">
                        

                         <form enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ secure_url('admin/xml/') }}">
                            <!-- CSRF Token -->

                            <div class="form-group {{ $errors->
                                first('state_id', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Seleccione el producto 
                                </label>
                                <div class="col-sm-5">
                                    
                                    <select id="id_producto" name="id_producto" class="form-control select2">

                                        <option value="0">Todos</option>

                                        @foreach($productos as $lp)

                                        <option      value="{{ $lp->id }}">
                                                {{ $lp->nombre_producto.' '.$lp->referencia_producto}}</option>
                                        @endforeach
                                        
                                      
                                    </select>
                                </div>
                                
                            </div> 

                            <div class="form-group col-sm-5  sm-offset-2">
                                
                                <button type="button" class="btn btn-primary addproducto">Agregar</button>

                            </div>

                            {{ csrf_field() }}

                    </form>

                    </div>


                    <div class="row">
                        

                        <div class="col-sm 12">
                            
                            <table class="table table-striped" id="tableAlmacen">
                                
                                <thead>
                                    <tr>
                                        
                                        <th>
                                            Imagen
                                        </th>
                                        <th>
                                            Nombre
                                        </th>
                                        <th>Referencia</th>
                                        <th>
                                            Referencia Sap
                                        </th>

                                        <th>
                                            Inventario
                                        </th>

                                        <th>
                                            Estado del producto
                                        </th>


                                         <th>
                                            Accion
                                        </th>


                                        </tr>
                                </thead>
                            

                        <tbody>

                      

                        </tbody>
    
                        </table>


                        </div>
                    </div>

                           

                    
                   
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

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

<script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>

<script>

    $(".select2").select2();

    $(document).ready(function(){

        base=$('#base').val();
        id_almacen=$('#id_almacen').val();

        var table =$('#tableAlmacen').DataTable({
            "processing": true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": base+'/admin/almacenes/'+id_almacen+'/datagestionar'
            }
        });

        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        });


         $('.addproducto').on('click', function(){

        base = $('#base').val();

        id_producto = $('#id_producto').val();

        id_almacen = $('#id_almacen').val();

         $.ajax({
            type: "GET",
            url: base+"/admin/almacenes/"+id_almacen+"/addproducto/"+id_producto,
                
            complete: function(datos){     

                table.ajax.reload();

            }
        });

    });


     $(document).on('click', '.delproducto', function(){

        base = $('#base').val();

        id = $(this).data('id');

         $.ajax({
            type: "GET",
            url: base+"/admin/almacenes/"+id+"/delproducto",
                
            complete: function(datos){     

                table.ajax.reload();

            }
        });

    });


     



    });


   






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
                            url: base+'/configuracion/citiestodos/'+stateID,
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