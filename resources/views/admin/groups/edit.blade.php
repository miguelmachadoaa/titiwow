@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('groups/title.edit')
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        @lang('groups/title.edit')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('groups/title.groups')</li>
        <li class="active">@lang('groups/title.edit')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('groups/title.edit')
                    </h4>
                </div>
                <div class="panel-body">
                    @if($role)
                        {!! Form::model($role, ['url' => secure_url('admin/groups/'. $role->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <div class="form-group {{ $errors->
                                first('name', 'has-error') }}">
                                <label for="title" class="col-sm-2 control-label">
                                    @lang('groups/form.name')
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" id="name" name="name" class="form-control"
                                           placeholder=@lang('groups/form.name') value="{!! old('name', $role->
                                    name) !!}">
                                </div>
                                <div class="col-sm-4">
                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                            <div class="form-group  {{ $errors->first('tipo', 'has-error') }}">
                                <label for="select21" class="col-sm-2 control-label">
                                    Tipo de Rol
                                </label>
                                <div class="col-sm-5">   
                                 <select id="tipo" name="tipo" class="form-control ">
                                        <option @if($role->tipo=='1') {{'Selected'}} @endif value="{{ 1 }}"  >Backend</option>
                                        <option @if($role->tipo=='2') {{'Selected'}} @endif  value="{{ 2}}" >Frontend</option>
                                       
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
                                    <input type="number" min="0" step="1" id="monto_minimo" name="monto_minimo" class="form-control"  placeholder="Monto Minimo Compra" value="{!! old('monto_minimo', $role->monto_minimo) !!}">
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
                                        <option  @if($role->oferta=='0') {{'Selected'}} @endif value="{{ 0 }}"  >Mostrar Ofertas </option>
                                        <option  @if($role->oferta=='1') {{'Selected'}} @endif value="{{ 1}}" >No Mostrar Ofertas</option>
                                       
                                </select>
                                <div class="col-sm-4">
                                    {!! $errors->first('oferta', '<span class="help-block">:message</span> ') !!}
                                </div>
                                  
                                </div>
                               
                            </div>



                            <div class="form-group">
                                <label for="slug" class="col-sm-2 control-label">@lang('groups/form.slug')</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" value="{!! $role->slug !!}" readonly />
                                </div>
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
                    @else
                        <h1>@lang('groups/message.error.no_role_exists')</h1>
                            <a class="btn btn-danger" href="{{ route('admin.groups.index') }}">
                                @lang('button.back')
                            </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>

@stop
