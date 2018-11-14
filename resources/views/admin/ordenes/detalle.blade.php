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


    <style type="text/css">
        
        .bs-wizard {margin-top: 40px;}

/*Form Wizard*/
.bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
.bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
.bs-wizard > .bs-wizard-step + .bs-wizard-step {}
.bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
.bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
.bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
.bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
.bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
.bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
.bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
.bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
.bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
.bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
.bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
.bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
.bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
/*END Form Wizard*/

    </style>


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
                        Etapa de la Orden {{$orden->referencia}}
                    </h4>
                </div>
                <div class="panel-body">

                    

                    <div class="col-sm-12">

            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step @if($orden->estatus=='1') active @elseif($orden->estatus>1) complete @else disabled @endif">
                  <div class="text-center bs-wizard-stepnum">Recibido</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-success text-center">1</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step @if($orden->estatus==5) active @elseif($orden->estatus>5) complete @else disabled @endif"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Aprobado</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">2</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step @if($orden->estatus=='6') active @elseif($orden->estatus>6) complete @else disabled @endif"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Facturado</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-primary text-center">3</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step @if($orden->estatus=='7') active @elseif($orden->estatus>7) complete @else disabled @endif"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">Enviado</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-danger text-center"> 4</div>
                </div>
            </div>
        
        
        
        

</div>

                  
                    
            
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->





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
                    <a class="btn btn-default" href="{{ url('admin/ordenes') }}">Regresar</a>

            </p>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->


    @if(isset($pago->id))


     <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Detalle de pago 
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
                 
                 <tbody>
                   
                        <tr>
                            <td><b>Id Orden</b></td>
                            <td>{{$pago->id_orden}}</td>
                            
                        </tr>

                        <tr>
                            <td>Forma de pago</td>
                            <td>{{$pago->nombre_forma_pago}}</td>
                            
                        </tr>

                        <tr>
                            <td>Monto</td>
                            <td>{{$pago->monto_pago}}</td>
                            
                        </tr>

                        <tr>
                            <td>collection_id</td>
                            <td>{{json_decode($pago->json)->collection_id}}</td>
                            
                        </tr>

                         <tr>
                            <td>collection_status</td>
                            <td>{{json_decode($pago->json)->collection_status}}</td>
                            
                        </tr>

                         <tr>
                            <td>collection_id</td>
                            <td>{{json_decode($pago->json)->collection_id}}</td>
                            
                        </tr>

                         <tr>
                            <td>preference_id</td>
                            <td>{{json_decode($pago->json)->preference_id}}</td>
                            
                        </tr>

                         <tr>
                            <td>external_reference</td>
                            <td>{{json_decode($pago->json)->external_reference}}</td>
                            
                        </tr>

                         <tr>
                            <td>payment_type</td>
                            <td>{{json_decode($pago->json)->payment_type}}</td>
                            
                        </tr>

                        <tr>
                            <td>merchant_order_id</td>
                            <td>{{json_decode($pago->json)->merchant_order_id}}</td>
                            
                        </tr>
                   

                    

                 </tbody>
             </table>
                    
           
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->

    @endif



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
