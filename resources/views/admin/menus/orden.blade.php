@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Menús
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ secure_asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />


    <link href="{{ secure_asset('assets/css/pages/sortable.css') }}" rel="stylesheet" type="text/css">
@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Menús</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Menús</a></li>
        <li class="active">Listado</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Menús
                    </h4>
                    <div class="pull-right">
                    <a href="{{ secure_url('admin/menus/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Crear Menú</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">

                        


                        <div class="dd" id="nestable_list_1">
                            <ol class="dd-list">

                                @foreach($menus as $m)

                                    <li class="dd-item" data-id="{{$m->id}}">
                                        <div class="dd-handle">{{$m->name}}</div>

                                        @if(isset($m->hijos))
                                            <ol class="dd-list">
                                                @foreach($m->hijos as $hijos)

                                                    <li class="dd-item" data-id="{{$hijos->id}}">
                                                        <div class="dd-handle">{{$hijos->name}}</div>

                                                            @if(isset($hijos->hijos))

                                                            <ol class="dd-list">

                                                                @foreach($hijos->hijos as $hh)

                                                                    <li class="dd-item" data-id="{{$hijos->id}}">
                                                                        <div class="dd-handle">{{$hijos->name}}
                                                                        </div>
                                                                    </li>

                                                                @endforeach

                                                                </ol>

                                                            @endif


                                                        
                    

                                                    </li>
                                                
                                                @endforeach

                                            </ol>

                                        @endif
                                    </li>

                                @endforeach
            
                            </ol>
                        </div>

                        <br>
                        <br>

                        <button type="button" id="guardar" class="btn btn-primary  ">Guardar </button>


  
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

<input type="hidden" name="base" id="base" value="{{secure_url('/')}}">


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

     <script type="text/javascript" src="{{ secure_asset('assets/vendors/nestable-list/jquery.nestable.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/vendors/html5sortable/html.sortable.js') }}"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/pages/ui-nestable.js') }}"></script>




<script>


    


var UINestable = function () {

    var updateOutput = function (e) {

        var list = e.length ? e : $(e.target),

            output = list.data('output');

        $('#guardar').on('click', function(){

            orden=window.JSON.stringify(list.nestable('serialize'));

                base=$('#base').val();

                _token=$('#_token').val();

                $.ajax({
                type: "POST",
                data:{ orden,  _token },
                url: base+"/admin/menus/"+"1"+"/postordenar",
                    
                complete: function(datos){   

                    $( location ).attr("href", base+"/admin/menus/");  

                }
            });


        });
    };


    return {
        //main function to initiate the module
        init: function () {

            // activate Nestable for list 1
            $('#nestable_list_1').nestable({
                group: 1
            })
                .on('change', updateOutput);

            // activate Nestable for list 2
            $('#nestable_list_2').nestable({
                group: 1
            })
                .on('change', updateOutput);

            // output initial serialised data
            updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));
            updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));

            $('#nestable_list_menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            $('#nestable_list_3').nestable();

        }

    };

}();
    


</script>


@stop
