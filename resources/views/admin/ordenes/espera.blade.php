@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Ordenes En Espera
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Ordenes En Espera</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Ordenes En Espera</a></li>
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
                       Ordenes En Espera
                    </h4>
                    <div class="pull-right">
                  <!--  <a href="{{ route('admin.ordenes.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear categoria</a>-->
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @livewire('espera-list')
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>



@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')

<script>
 window.livewire.on('openModal', () => {

  //   alert('evento');

     $('#exampleModal').modal('show');
 })
 </script>

@stop
