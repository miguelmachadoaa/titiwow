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
    <h1>Precios de Productos</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Escritorio
            </a>
        </li>
        <li>Precios Productos</li>
        <li class="active">Listado de Productos</li>
    </ol>
</section>

<section class="content paddingleft_right15">
    <div class="row">
     
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Listado de Productos
                </h4>
                <div class="pull-right">
                    
                </div>
            </div>
            <br />



            
            <div class="panel-body table-responsive">

                <div style="margin-bottom: 1em; margin-top: 1em;" class="row">
                    
                        <form class="" method="post" action="{{secure_url('admin/productos/postgrid')}}" target="_blank">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                    <label for="select21" class="col-md-3 control-label">
                                       Productos
                                    </label>

                                    

                                            <div class="col-md-7">  

                                             <select style="width: 100%; height: 2.5em;" style="width: 50%" id="id_producto" name="id_producto" class="form-control select2 js-example-responsive">

                                                <option value="">Seleccione</option>
                                                    
                                                    @foreach($productos as $pro)

                                                        @if(isset($inventario[$pro->id]))
                                                        
                                                        <option value="{{ $pro->id }}">{{ $pro->nombre_producto.' - '.$pro->presentacion_producto.' - '.$pro->referencia_producto.' - Disp:'.$inventario[$pro->id]}}
                                                        </option>

                                                        @endif

                                                    @endforeach

                                            </select>

                                              {!! $errors->first('id_producto', '<span class="help-block">:message</span> ') !!}
                                            </div>


                                            <div class="col-md-2">   

                                                <button type="button" class="btn btn-primary addProductoCupon" > Agregar</button>
                                             

                                              
                                            </div>
                                               
                                       


                 <div class="col-sm-12 listaProducos"> 

                                                
                                            <table class="table table-responsive" id="tableListProductos">
                                                <thead>
                                                    <tr>
                                                        <td>Producto</td>
                                                        <td>Accion</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>    


                                        </div>
        



                  <div class="clearfix"></div>

                <div class="col-sm-3">
                    <br>
                    <button type="submit" class="btn btn-default">Generar</button>

                </div>

                  
                </form>




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


$(document).on('click', '.delProductoCombo', function(){

    id=$(this).data('id');

    $('#tr'+id+'').remove();
});
     

$('.addProductoCupon').click(function(){

    id_producto=$('#id_producto').val();

    name=$('select[name="id_producto"] option:selected').text();


    include='';

    if (id_producto !='') {

         include=include+'<tr id="tr'+id_producto+'">';
            include=include+'<td>'+name+'</td>';
            include=include+'<td> <button data-id="'+id_producto+'" class="btn btn-danger delProductoCombo"><i class="fa fa-trash "></i></button> <input type="hidden" name="c_pro_'+id_producto+'" id="c_pro_'+id_producto+'" value="'+id_producto+'"> </td>';
            include=include+'</tr>';

        $('#tableListProductos tbody').append(include);

    }


});





     </script>


    @stop

