@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Detalle Almacen
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Detalle Almacen
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
                       Detalle  Almacen
                    </h4>
                </div>
                <div class="panel-body">
                    
                        
                            <h3>Productos Activos en el Almacen</h3>
                            
                            <table class="table table-striped" id="tableAlmacen">
                                
                                <thead>
                                    <tr>
                                       
                                        <th>
                                            Id
                                        </th>
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

                                       
                                    </tr>
                                </thead>
                            

                        <tbody>

                        @foreach($productos as $p)

                        <tr>
                            <td>
                                {{$p->id}}
                            </td>

                            <td>
                                <img style="width: 60px;" src="{{secure_url('uploads/productos/'.$p->imagen_producto)}}" alt="img">
                            </td>

                            <td>
                                {{$p->nombre_producto}}
                            </td>
                            <td>
                                {{$p->referencia_producto}}
                            </td>

                            <td>
                                {{$p->referencia_producto_sap}}
                            </td>
                            <td>
                                @if(isset($inventario[$p->id][$almacen->id]))

                                    {{$inventario[$p->id][$almacen->id]}}

                                @else

                                    {{'0'}}

                                @endif
                            </td>

                           
                        </tr>



                       
    
                        @endforeach

                        </tbody>
    
                        </table>

                     
                   
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

<script>


    $('#tableAlmacen').DataTable();

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