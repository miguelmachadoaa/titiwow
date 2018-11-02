
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mis Compras
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i>Inicio
                    </a>
                </li>
                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('clientes/') }}">Mi Perfil </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('miscompras/') }}">Compras </a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> Mis Compras 
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="products">

        <h3>    Mis Compras  </h3>
        
        <br>    

        <div class="row">
        @if(!$compras->isEmpty())

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
                            {{ $row->monto_total }}
                        </td>
                        <td>
                            {{ $row->created_at }}
                        </td>

                        <td>    
                               

                                  <button class="btn btn-info btn-xs seeDetalle" data-url="{{ url('clientes/'.$row->id.'/detalle') }}" data-id="{{ $row->id }}" href="{{ url('clientes/'.$row->id.'/detalle') }}">
                                    <i class="livicon "  data-name="eye" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Ver Detalle"></i>
                                 </button>

                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                          

                        </td>
                    </tr>
                
            @endforeach
             </table>
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen Compras aun.
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
    <script src="{{ asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
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