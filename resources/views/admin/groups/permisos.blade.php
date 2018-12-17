@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('groups/title.management')
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>@lang('groups/title.management')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('groups/title.groups')</a></li>
        <li class="active">@lang('groups/title.groups_list')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('groups')
                    </h4>
                    <div class="pull-right">
                    <a href="{{ route('admin.groups.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">


                
        {{ Form::open(array('url' => route('admin.groups.save', $role->id), 'class' => 'form-horizontal')) }}
    <ul>

    <div class="row">



    @foreach($listado as $key=>$value)


        <div class="col-md-4">
        

            {{Form::label($key, $key, ['class' => 'form col-md-4 capital_letter'])}}
            <br>

            <select id="{{ $key }}[]" name="{{ $key }}[]" class="select form-control" multiple="multiple">

                    @foreach($value as $l)

                    <option   @if(isset($acceso[$key.'.'.$l])) selected @endif  value="{{ $l }}">{{ $l }}</option>

                    @endforeach

               
            </select>   

        </div>

    @endforeach

    </div>  




    <br>

      <div class="form-group">

            <div class="col-sm-offset-3 col-sm-3">

                {!! Form::submit('Guardar', ['class' => 'btn btn-success form-control']) !!}

            </div>

            <a href="{{$role->is_group ? route('admin.groups.index'):route('admin.groups.index')}}" class="btn btn-default">Regresar</a>

    </div>
        
        </div> <!-- /panel body  -->
        </div>

    </ul> 
    {{ Form::close() }}     



                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<div class="modal fade" id="users_exists" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                @lang('groups/message.users_exists')
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>


<script>


     $(document).ready(function() {

            $('#table').DataTable();
            
        });

    $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
    $(document).on("click", ".users_exists", function () {

        var group_name = $(this).data('name');
        $(".modal-header h4").text( group_name+" Group" );
    });</script>
@stop
