@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    @lang('admin/groups/title.create')
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        @lang('groups/title.create')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('groups/title.groups')</li>
        <li class="active">
            @lang('groups/title.create')
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('groups/title.create')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! $errors->first('slug', '<span class="help-block">Another role with same slug exists, please choose another name</span> ') !!}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="post" action="{{ secure_url('admin/groups/store') }}">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('name', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                @lang('groups/form.name')
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Group Name"
                                       value="{!! old('name') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('name', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        <div class="form-group  {{ $errors->first('tipo', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Tipo de Rol
                                </label>
                                <div class="col-sm-5">   
                                 <select id="tipo" name="tipo" class="form-control ">
                                        <option  value="{{ 1 }}"  >Backend</option>
                                        <option   value="{{ 2}}" >Frontend</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('tipo', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>


                             <!--div class="form-group {{ $errors->
                                first('monto_minimo', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    Monto Minimo Compra
                                </label>
                                <div class="col-sm-5">
                                    <input type="number" min="0" step="1" id="monto_minimo" name="monto_minimo" class="form-control"  placeholder="Monto Minimo Compra" value="{!! old('monto_minimo') !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('monto_minimo', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div-->


                            <div class="form-group  {{ $errors->first('oferta', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Mostrar Oferta 
                                </label>
                                <div class="col-sm-5">   
                                 <select id="oferta" name="oferta" class="form-control ">
                                        <option   value="{{ 0 }}"  >Mostrar Ofertas </option>
                                        <option   value="{{ 1}}" >No Mostrar Ofertas</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('oferta', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>



                        <div style="overflow-x:auto;">
                            <table class="table table-striped table-responsive">
                                    <tr>
                                        <th colspan="8" class="text-center">Conceder Permisos</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Módulo</th>
                                        <th class="text-center">Index</th>
                                        <th class="text-center">Create</th>
                                        <th class="text-center">Store</th>
                                        <th class="text-center">Show</th>
                                        <th class="text-center">Edit</th>
                                        <th class="text-center">Update</th>
                                        <th class="text-center">Destroy</th>
                                    </tr>
                                
                                    @foreach($modulos as $modulo)
                                                <tr>
                                                    <td class="text-center"><input type="text" name="permissions[]"
                                                               value="{{ $modulo->nombre_modulo }}" readonly></td>

                                                    <td class="text-center">
                                                        <input type="checkbox" name="permissions[]"
                                                               value="index">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"  name="permissions[]"
                                                               value="create">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"  name="permissions[]"
                                                               value="store">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"  name="permissions[]"
                                                               value="show">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"  name="permissions[]"
                                                               value="edit">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"  name="permissions[]"
                                                               value="update">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"  name="permissions[]"
                                                               value="destroy">
                                                    </td>
                                                </tr>
                                    @endforeach
                                    
                                </table>
                            </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('admin.groups.index') }}">
                                    @lang('button.cancel')
                                </a>
                                <button type="submit" class="btn btn-success">
                                    @lang('button.save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop

