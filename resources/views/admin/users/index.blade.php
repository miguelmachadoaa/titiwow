@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Lista de usuarios
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Usuarios</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="#"> Usuarios</a></li>
        <li class="active">Lista de usuarios</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Users List
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-responsive table-bordered width100" id="table">
                    <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>E-mail</th>
                            <th>Estado</th>
                            <th>Fecha de creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>    <!-- row-->

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

<script>
    $(function() {
        base=$('#base').val();

        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: base+'/admin/users/data',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'status', name: 'status'},
                { data: 'created_at', name:'created_at'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );
    });

</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
  </div>
</div>
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script>
@stop
