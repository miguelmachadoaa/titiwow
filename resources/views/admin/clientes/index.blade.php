@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Todos los @lang('clientes/title.clientes')
@parent
@stop

@section('header_styles')

@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1> Todos los @lang('clientes/title.clientes')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Todos los @lang('clientes/title.clientes') </a></li>
        <li class="active"> Inicio</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Todos los @lang('clientes/title.clientes')
                    </h4>
                    <div class="pull-right">
                    <a href="{{ route('admin.clientes.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>  @lang('clientes/title.create')</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    @livewire('clientes-list')

                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')


@stop
