
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mis Referidos 
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
                    <a href="{{ url('clientes') }}">Area Cliente </a>
                </li>

                <li class="hidden-xs">
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#01bc8c" data-hc="#01bc8c"></i>
                    <a href="{{ url('clientes/miscompras') }}">Mis Compras </a>
                </li>
            </ol>
            <div class="pull-right">
                <i class="livicon icon3" data-name="edit" data-size="20" data-loop="true" data-c="#3d3d3d" data-hc="#3d3d3d"></i> Mis Referidos 
            </div>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="products">

        <h3>    Mis referidos </h3>
        <a class="btn btn-info" href="{{ url('registroembajadores/'.'ALP'.$user->id) }}">Registrar Embajador</a>

        <div class="row">
        @if(!$referidos->isEmpty())

             <table class="table table-responsive">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Puntos</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>


            @foreach($referidos as $referido)

                    <tr>
                        <td>
                            {{ $referido->first_name }}
                        </td>
                        <td>
                            {{ $referido->last_name }}
                        </td>
                        <td>
                            {{ $referido->email }}
                        </td>
                        <td>
                            {{ $referido->puntos }}
                        </td>
                        <td>
                            {{ $referido->created_at }}
                        </td>

                        <td>    
                                <a href="{{ route('clientes.index', $referido->id) }}">
                                    <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="editar categoria"></i>
                                 </a>

                                  <a href="{{ url('clientes/'.$referido->id_user_client.'/compras') }}">
                                    <i class="livicon" data-name="eye" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Ver Compras"></i>
                                 </a>



                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                          

                        </td>
                    </tr>
                
            @endforeach
             </table>
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen Referidos aun.
            </div>
        @endif
        </div>
        
    </div>
</div>
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

        })


    </script>
@stop