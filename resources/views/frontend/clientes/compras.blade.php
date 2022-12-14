
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Compras Mis Referidos 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
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
                    <a href="{{ secure_url('clientes') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="{{ secure_url('clientes/miscompras') }}">Compras  </a>
                </li>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="products">

        <h3>    Compras del Embajador  {{ $user->first_name.' '.$user->last_name }}  </h3>
        
        <br>    

        <div class="row">
        @if(!$compras->isEmpty())

         <div class="table-responsive">

             <table class="table table-responsive">
                    <tr>
                        <th>Id</th>
                        <th>Nombre Cliente</th>
                        <th>Forma Envio</th>
                        <th>Forma de Pago </th>
                        <th>Monto Total </th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>


            @foreach($compras as $row)

                    <tr>
                        <td>
                            {{ $row->id }}
                        </td>
                        <td>
                            {{ $row->first_name.' '.$row->last_name }}
                        </td>
                        <td>
                            {{ $row->nombre_forma_envios }}
                        </td>
                        <td>
                            {{ $row->nombre_forma_pago }}
                        </td>
                        <td>
                            {{  number_format($row->monto_total,0,",",".") }}
                        </td>
                        <td>
                            {{ $row->created_at }}
                        </td>

                        <td>    
                               

                                  <button class="btn btn-info btn-xs seeDetalle" data-url="{{ secure_url('clientes/'.$row->id.'/detalle') }}" data-id="{{ $row->id }}" href="{{ secure_url('clientes/'.$row->id.'/detalle') }}">
                                    <i class="livicon "  data-name="eye" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Ver Detalle"></i>
                                 </button>

                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                          

                        </td>
                    </tr>
                
            @endforeach
             </table>

         </div>
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen Referidos aun.
            </div>
        @endif
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

                         <table class="table table-responsive" id="tbDetalle"> 

                        </table>
                        
                        


                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal Direccion -->



@endsection

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('.addtocart').on('click', function(e){

            e.preventDefault();

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

                if (data.resultado) {

                    $('#detalle_carro_front').html(data.contenido);
                         
                }

            });

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