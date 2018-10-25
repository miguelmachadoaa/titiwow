@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Reportes Clientes Registrados
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Reporte de Clientes Registrados</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Reportes </a></li>
        <li class="active">Clientes Registrados</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Reportes Clientes Registrados
                    </h4>
                </div>
                <br />
                <div class="panel-body">
                    <a href="{{ url('reportes/registrados/registradosexcel') }}" class="btn btn-sm btn-primary">
                        Descargar productos en Excel
                    </a>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop
@section('footer_scripts')

<script>
    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });
</script>
@stop
