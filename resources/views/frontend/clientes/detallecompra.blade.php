
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Detalle de Compra
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>


    <style>
        
        .update-nag{
  display: inline-block;
  font-size: 14px;
  text-align: left;
  background-color: #fff;
  height: 40px;
  -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.2);
  box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
  margin-bottom: 10px;
}

.update-nag:hover{
    cursor: pointer;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.4);
  box-shadow: 0 1px 1px 0 rgba(0,0,0,.3);
}

.update-nag > .update-split{
  background: #337ab7;
  width: 33px;
  float: left;
  color: #fff!important;
  height: 100%;
  text-align: center;
}

.update-nag > .update-split > .glyphicon{
  position:relative;
  top: calc(50% - 9px)!important; /* 50% - 3/4 of icon height */
}
.update-nag > .update-split.update-success{
  background: #5cb85c!important;
}

.update-nag > .update-split.update-danger{
  background: #d9534f!important;
}

.update-nag > .update-split.update-info{
  background: #5bc0de!important;
}



.update-nag > .update-text{
  line-height: 18px;
  padding-top: 5px;
  padding-left: 45px;
  padding-right: 5px;
}



    </style>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="{{ secure_url('clientes/') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="{{ secure_url('miscompras/') }}">Compras </a>
                </li>
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="welcome">
            <h3>Mis Compras</h3>
        </div>
        <hr>

        <a target="_blank" class="btn btn-info" href="{{secure_url('tracking/'.$orden->token)}}">Rastrea Tu Envio </a>


        <!--h3>Historico de Envio </h3>

        <div class="row">

            @foreach($history_envio  as  $row)


             <div class="col-md-12">
              <div class="update-nag">
                <div class="update-split">
                    <i class="glyphicon {{ $iconos[$row->estatus_envio]}}"></i>
                </div>
                <div class="update-text">{{ $row->estatus_envio_nombre }} <a href="#">{!! $row->created_at->diffForHumans() !!}</a> </div>
              </div>    
            </div>


            @endforeach
        
        
        </div-->






    <div class="row">

        <div class="col-sm-12">

             <h3>Detalle de Compra </h3>


            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col"> Imagen</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Precio Unitario</th>
                            <th scope="col" class="text-center">Cantidad</th>
                            <th scope="col" class="text-right">Total </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach( $detalles as $row)

                        <tr>
                            <td><img height="60px" src="{{ secure_url('/') }}/uploads/productos/60/{{$row->imagen_producto}}"> </td>
                             <td>{{$row->referencia_producto}}</td>
                            <td>{{$row->nombre_producto}}</td>
                            <td>{{number_format($row->precio_unitario,2)}}</td>
                            <td> {{ $row->cantidad }} </td>
                            <td>{{ number_format($row->precio_total, 2) }}</td>
                            
                        </tr>

                        @endforeach
                        
                       
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Sub-Total</td>
                            <td class="text-right" >{{ number_format($orden->monto_total_base, 2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Envio</td>
                            <td class="text-right">


                                @if(isset($envio->costo))
                              @if(intval($envio->costo)==0)

                                {{'Gratis'}}

                              @else

                                {{ number_format($envio->costo, 2) }}

                              @endif

                          @else

                            {{'Gratis'}}


                          @endif




                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>Total</strong></td>

                            @if(isset($envio->costo))
                            <td class="text-right"><strong>{{ number_format($envio->costo+$orden->monto_total, 2) }}</strong></td>

                            @else
                             <td class="text-right"><strong>{{ number_format($orden->monto_total, 2) }}</strong></td>

                            @endif
                        </tr>


                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>Descuento</strong></td>
                            <td class="text-right"><strong>{{ number_format($orden->monto_total_base-$orden->monto_total, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

        
        </div>

</div>
</div>
</div>
<div class="container">
    <div class="form-group">
        <div class="col-lg-offset-5 col-lg-10" style="margin-bottom:20px;">
            <a class="btn btn-danger" type="button" href="{{ secure_url('clientes') }}">Regresar</a>
        </div>
    </div>
</div>



<!-- Modal Detalle -->
 <div class="modal fade" id="detalleModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Detalle de Compra</h4>
                    </div>
                    <div class="modal-body">
                        <h3>Detalle de la Compra</h3>
                         <div class="" id="tbDetalle"> 

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->



<!-- Modal Direccion -->
 <div class="modal fade" id="confirmarOrdenModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Confirmar Orden</h4>
                    </div>
                    <div class="modal-body">
                        
                        <form method="POST" action="{{secure_url('ordenes/confirmar')}}" id="confirmarOrdenForm" name="confirmarOrdenForm" class="form-horizontal">

                            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                            <input type="hidden" name="confirm_id" id="confirm_id" value="">

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>¿Estas Seguro que quieres cancelar el pedido?</h3>
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Volver</button>
                        <button type="button" class="btn  btn-primary sendConfirmar" >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->


<input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">


@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>

    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>


    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $("#confirmarOrdenForm").bootstrapValidator({
    fields: {
        
        id_status: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar un estatus'
                }
            }
        }
    }
});


        $('body').on('click','.confirmar', function(){

        $('#confirm_id').val($(this).data('id'));

        $('#cod_oracle_pedido').val($(this).data('codigo'));

        $('#id_status').val($(this).data('estatus'));

            $("#confirmarOrdenModal").modal('show');
        });



        $('.sendConfirmar').click(function () {
    
    var $validator = $('#confirmarOrdenForm').data('bootstrapValidator').validate();

    if ($validator.isValid()) {

        base=$('#base').val();
        confirm_id=$('#confirm_id').val();
        id_status=4;
        cod_oracle_pedido=$('#cod_oracle_pedido').val();
        notas='Cancelado por el cliente';


        $.ajax({
            type: "POST",
            data:{ base, confirm_id, id_status, cod_oracle_pedido, notas },
            url: base+"/admin/ordenes/storeconfirm",
                
            complete: function(datos){     

                $(".estatus_"+confirm_id+'').html(datos.responseText);

                $('#confirmarOrdenModal').modal('hide');
                
                $('#confirm_id').val('');
            
            }
        });


        //document.getElementById("addDireccionForm").submit();


    }

});













        $('.seeDetalle').on('click', function(){

            $('#detalleModal').modal('show');

            id=$(this).data('id');

            url=$(this).data('url');

            $.get(url, {}, function(data) {

                $('#tbDetalle').html(data);

            });

        })


    </script>
@stop