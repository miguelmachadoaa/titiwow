@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Orden {{$orden->id}}
@parent
@stop


{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/css/pages/timeline.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/timeline2.css') }}" rel="stylesheet" />
    
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <!--end of page level css-->
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Ver Orden {{$orden->id}}
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Ordenes</li>
        <li class="active">Ver</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Orden {{$orden->referencia}}
                    </h4>
                </div>
                <div class="panel-body">

                     <br>
                        <div class="row">   
                            <div class="col-sm-12">  
                                    <h3>    Detalle de la orden</h3>
                             </div>
                            
                        </div>

                    <br> 

                   <table class="table table-striped ">
                 <thead>
                     <tr>
                         <th>Imagen</th>
                         <th>Producto</th>
                         <th>Precio</th>
                         <th>Cantidad</th>
                         <th>SubTotal</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($detalles as $row)
                        <tr>
                            <td><img height="60px" src="{{ url('/') }}/uploads/productos/{{$row->imagen_producto}}"></td>
                            <td>{{$row->nombre_producto}}</td>
                            <td>{{number_format($row->precio_unitario,2)}}</td>
                            <td>
                                {{ $row->cantidad }}

                            </td>
                            <td>{{ number_format($row->precio_total, 2) }}</td>
                        </tr>
                     @endforeach

                     <tr>
                         <td style="text-align: right;" colspan="4"><b> Total: </b></td>
                         <td >{{ number_format($orden->monto_total, 2) }}</td>
                     </tr>

                 </tbody>
             </table>
                    
            <p style="text-align: center;"> 
                    <a class="btn btn-default" href="{{ url('admin/ordenes') }}">Volver</a>

            </p>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->





    <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Historia de Cambios
                                </h3>
                                
                            </div>
                            <div class="panel-body">
                                <!--timeline-->
                                <div class="row">
                                    <ul class="timeline">


                                         @foreach($history as $indexKey => $row)

                                            @if($indexKey%2==0)

                                            <li>
                                            <div class="timeline-badge">
                                                <i class="livicon" data-name="users" data-c="#fff" data-hc="#fff" data-size="18" data-loop="true"></i>
                                            </div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">{{ ucwords($row->estatus_nombre) }}</h4>
                                                    <p>
                                                        <small class="text-muted">
                                                            <i class="livicon" data-name="bell" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
                                                            {!! $row->created_at->diffForHumans().' por  '.$row->first_name.' '.$row->last_name !!}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p>
                                                        {{ $row->notas }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>

                                            @else

                                             <li class="timeline-inverted">
                                            <div class="timeline-badge">
                                                <i class="livicon" data-name="users" data-c="#fff" data-hc="#fff" data-size="18" data-loop="true"></i>
                                            </div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">{{ ucwords($row->estatus_nombre) }}</h4>
                                                    <p>
                                                        <small class="text-muted">
                                                            <i class="livicon" data-name="bell" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
                                                            {!! $row->created_at->diffForHumans().' por  '.$row->first_name.' '.$row->last_name !!}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p>
                                                        {{ $row->notas }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>

                                            @endif
                                         

                                         @endforeach


                                    </ul>
                                </div>
                                <!--timeline ends-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--timeline2-->










</section>


<!-- Main content -->

@stop


{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ asset('assets/vendors/summernote/summernote.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>

<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

@stop
