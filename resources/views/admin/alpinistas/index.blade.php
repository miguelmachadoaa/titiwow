@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Alpinistas
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Alpinistas</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Alpinistas</a></li>
        <li class="active">Index</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Alpinistas
                    </h4>
                    <div class="pull-right">
                        <a href="{{ secure_url('admin/alpinistas/create') }}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span> Cargar Alpinistas</a>
                        <a href="{{ secure_url('admin/alpinistas/show') }}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-minus"></span> Retirar Alpinistas</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">

                    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
                    
                    @if ($alpinistas->count() >= 1)
                        <div class="table-responsive">

                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Documento</th>
                                    <th>Cod. Alpinista</th>
                                    <th>ID Masterfile</th>
                                    <th>Estado Alpinista</th>
                                    <th>Creado</th>
                                </tr>
                            </thead>
                            <tbody>

                               
                            </tbody>
                        </table>
                        </div>
                    @else
                        No se encontraron registros
                    @endif   
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script>
      

     $(document).ready(function() {


                base=$('#base').val();

                //alert(base);
                
            var table =$('#table').DataTable( {
                "processing": true,
                "ajax": {
                    "url": base+'/admin/alpinistas/data/'
                }
            } );

            table.on( 'draw', function () {
                    $('.livicon').each(function(){
                        $(this).updateLivicon();
                    });
                } );


        } );


    </script>
@stop
