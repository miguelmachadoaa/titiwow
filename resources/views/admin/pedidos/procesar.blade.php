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
                <div class="panel-body" style="padding-top: 0;">
                     <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                     <div class="row" style="padding-top: 0;">

                        <div class="col-sm-6">

                            <h3>Datos de la compra  </h3>

                            <p>Nombre: {{$compra->first_name.' '.$compra->last_name}}</p>
                            <p>Forma de Pago: {{$compra->nombre_forma_envios}}</p>
                            <p>Forma de Envio: {{$compra->nombre_forma_pago}}</p>
                            
                            <h3>Enlace para pago </h3>

                            <p>{{secure_url('pedidos/'.$compra->token.'/pago')}}</p>

                        </div>

                        <div class="col-sm-6 table-responsive">

                            <h1>Detalle de Su Pedido</h1>
                            
                             <table class="table  ">
                                     <thead style="border-top: 1px solid rgba(0,0,0,0.1);">
                                         <tr>
                                             <th>Imagen</th>
                                             <th>Producto</th>
                                             <th>Precio</th>
                                             <th>Cantidad</th>
                                             <th>SubTotal</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @foreach($detalles as $row)
                                            <tr>
                                                <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" ><img height="60px" src="../uploads/productos/60/{{$row->imagen_producto}}"></a></td>
                                                <td><a target="_blank"  href="{{ secure_url('producto', [$row->slug]) }}" >{{$row->nombre_producto}}</a></td>
                                                <td>{{number_format($row->precio_unitario,0,",",".")}}</td>
                                                <td> {{ $row->cantidad }} </td>
                                                <td>{{ number_format($row->precio_total, 0,",",".") }}</td>
                                            </tr>
                                         @endforeach

                                         <tr>
                                             <td colspan="4" style="text-align: right;">
                                                 <b>Total: </b>
                                             </td>
                                             <td>
                                                 {{number_format($compra->monto_total, 0,",",".")}}
                                             </td>
                                         </tr>
                                         <tr>
                                             <td colspan="4" style="text-align: right;">
                                                 <b>Base Impuesto: </b>
                                             </td>
                                             <td>
                                                 {{number_format($compra->base_impuesto, 0,",",".")}}
                                             </td>
                                         </tr>

                                         <tr>
                                             <td colspan="4" style="text-align: right;">
                                                 <b>Iva:</b> {{ $compra->valor_impuesto*100 }}%: 
                                             </td>
                                             <td>
                                                 {{number_format($compra->monto_impuesto, 0,",",".")}}
                                             </td>
                                         </tr>

                                           <tr>
                                             <td colspan="4" style="text-align: right;">
                                                 <b>Monto Ahorrado: </b>
                                             </td>
                                             <td>
                                                 {{number_format($compra->monto_total_base-$compra->monto_total, 0,",",".")}}
                                             </td>
                                         </tr>

                                         

                                     </tbody>
                                 </table>



                        </div>

                        <div class="col-sm-12">
                            <a class="btn btn-primary" href="{{secure_url('admin/tomapedidos/')}}">Realizar Otro Pedido</a>
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


  
<!-- Modal Direccion -->
 <div class="modal fade" id="confirmarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Ingrese La Cantidad</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            {{ csrf_field() }}
                            <div class="row">

                                <input type="hidden"  name="idproducto_modal" id="idproducto_modal" >

                                

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Cantidad
                                    </label>
                                    <div class="col-md-8" >

                                        <input class="form-control" type="number" step="1" min="0" name="cantidad_modal" id="cantidad_modal" >
                                        
                                    </div>
                                </div>

                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-primary sendConfirmar" >Agregar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->
  
<!-- Modal Direccion -->


<!-- Modal Direccion -->










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


      $(document).on('click','.addproducto', function(){

             base=$('#base').val();

             id=$(this).data('id');

            $.get(base+'/admin/pedidos/'+id+'/addtocart', function(data) {

                    $('.listaorden').html(data);
            });
    });





    $(document).on('change','.cantidadcarrito', function(){

        base=$('#base').val();

        id=$(this).data('id');

        cantidad=$(this).val();

        if (cantidad==0) {

            $('#idproducto_modal').val(id);

            $('#confirmarOrdenModal').modal('show');


        }else{

            $.get(base+'/admin/pedidos/'+id+'/updatecart/'+cantidad, function(data) {

                    $('.listaorden').html(data);
            });


        }



         

    });


    $('.sendConfirmar').click(function(){

        base=$('#base').val();

        id=$('#idproducto_modal').val();

        cantidad=$('#cantidad_modal').val();

         $.get(base+'/admin/pedidos/'+id+'/updatecart/'+cantidad, function(data) {

                    $('.listaorden').html(data);

                    $('#confirmarOrdenModal').modal('hide');

            });

    });




</script>
@stop
