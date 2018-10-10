
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Mis Amigos 
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
                    <a href="{{ url('clientes/misamigos') }}">Mis Amigos </a>
                </li>
            </ol>
            
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="products">

        <h3>    Mis Amigos </h3>

        <div class="row" id="table_amigos">
        @if(!$referidos->isEmpty())

             <table class="table table-responsive" id="tbAmigos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Puntos</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

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

                                  <a class="btn btn-xs" href="{{ url('clientes/'.$referido->id_user_client.'/compras') }}">
                                    <i class="livicon" data-name="eye" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Ver Compras"></i>
                                 </a>

                                 <button class="btn btn-xs btn-danger eliminarAmigo" data-id="{{ $referido->id_user_client }}"> <i class="fa fa-trash"> </i>  </button>


                        </td>
                    </tr>
                
            @endforeach
            </tbody>
             </table>
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen Referidos aun.
            </div>
        @endif
        </div>
        
    </div>
</div>


<!-- Modal Direccion -->
 <div class="modal fade" id="delAmigoModal" role="dialog" aria-labelledby="modalLabeldanger">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabeldanger">Eliminar Amigo</h4>
                    </div>
                    <div class="modal-body">
                      
                           <input type="hidden" name="url" id="url" value="{{ url('clientes/deleteamigo') }}">

                            <input type="hidden" name="del_id" id="del_id" value="">

                            <h3> Esta seguro de eliminar su Amigo?</h3>

                    </div>

                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn  btn-danger sendEliminar" >Eliminar</button>
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


        $('#tbAmigos').on('click', '.eliminarAmigo',  function(e){

            e.preventDefault();

            $('#del_id').val($(this).data('id'));

            $('#delAmigoModal').modal('show');

        });

        $('.sendEliminar').on('click',  function(e){

            e.preventDefault();

            id=$('#del_id').val();

            url=$('#url').val();

            $.post(url, {id}, function(data) {

                    $('#table_amigos').html(data);

            $('#delAmigoModal').modal('hide');

                         
            });

        })


    </script>
@stop