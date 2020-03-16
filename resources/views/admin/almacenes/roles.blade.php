@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Gestionar Roles Almacen
@parent
@stop

@section('header_styles')

    <link href="{{ secure_asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{ secure_asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Gestionar Roles Almacen
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Almacens</li>
        <li class="active">Gestionar Roles</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Gestionar Roles del Almacen
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($almacen, ['url' => secure_url('admin/almacenes/'. $almacen->id.'/postroles'), 'method' => 'POST', 'class' => 'form-horizontal', 'files'=> true]) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}

                            <div class="row" style="margin-bottom: 2em;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-info marcar">Marcar Todos</button>
                                    <button type="button" class="btn btn-danger desmarcar">Desmascar Todos</button>
                                </div>
                            </div>

                        @foreach($roles as $p)

                        <div class="row">
                            <div class="col-sm-12">
                                 <div class="checkbox">
                                    <label>
                                      <input 
                                      class="cb " 
                                      id="p_{{$p->id}}" 
                                      name="p_{{$p->id}}" 
                                      @if(isset($check[$p->id]))
                                            {{'checked'}}
                                      @endif
                                      type="checkbox" > {{$p->name}}
                                    </label>
                                  </div>
                                
                            </div>
                        </div>
    
                        @endforeach

                       <div class="form-group">
                            <div class=" col-sm-6" style="margin-top: 2em;">
                                
                                <a class="btn btn-danger" href="{{ route('admin.almacenes.index') }}">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

    <!-- row-->
</section>

@stop
@section('footer_scripts')


<script src="{{ secure_asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ secure_asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script>

    $('.marcar').click(function(){


        $('.cb').each(function(){
            $(this).attr("checked", "checked");
        });
    });



    $('.desmarcar').click(function(){

        $('.cb').each(function(){
            $(this).removeAttr('checked');
        });
    });
    

      $('select[name="state_id"]').on('change', function() {
                    var stateID = $(this).val();
                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id"]').append('<option value="'+ key+'_'+value +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id"]').empty();
                    }
                });



</script>


@stop