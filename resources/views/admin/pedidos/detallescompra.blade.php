<div class="col-sm-12" style="padding-botton: 1em; ">

                        <div class="row" style=" padding: 0em 0em; border:  1px solid rgba(0,0,0,0.1); border-radius: 0.5em;">
                        
                            <div class="col-sm-12" style="    ">
                                
                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Subtotal</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                    <!--h4 style="color:#1450C9;">{{ number_format($total+$impuesto,0,",",".")}}</h4--> 
                                    <h4 style="color:#1450C9;">{{ number_format($total-$impuesto+$envio_base,0,",",".")}}</h4> 

                                </div>

                            </div>

                            <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">
                                
                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Impuesto</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                    <h4 style="color:#1450C9;">{{ number_format($impuesto+$envio_impuesto,0,",",".")}}</h4> 

                                </div>

                            </div>

                            <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">
                                
                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Envio</h4>
                                </div>
                                

                                <div class="col-sm-4 col-xs-4">

                                    <h4 style="color: #22b14c;">

                                    @if($costo_envio==-1)

                                    No Definido


                                    @elseif($costo_envio==0)

                                    Gratis

                                    @else
                                    
                                    {{ number_format($costo_envio,0,',','.')  }}

                                    @endif


                                    </h4> 

                                </div>
                                
                            </div>


                            @if(isset($descuentos))

                                @foreach($descuentos as $pago)

                                <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">
                
                                    <div class="col-sm-8 col-xs-8" >
                                        <h4 style="color: #d5006e;">Cupón {{ $pago->codigo_cupon }}</h4>
                                    </div>
                                    <div class="col-sm-4 col-xs-4">

                                        <h4 style="color: #22b14c;">{{   number_format($pago->monto_descuento,0,",",".") }}    <button data-id="{{ $pago->id }}" style="color: red !important; margin:0; padding: 0em 1em;" class="btn btn-link delCuponTomapedidos" ><i class="fa fa-trash"></i></button></h4> 

                                    </div>
                                    
                                </div>

                                @endforeach

                            @endif

                           
                        @if(isset($total))
                            <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">

                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Total a Pagar</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                        @if($costo_envio==-1)

                                            <h4 style="color:#1450C9;">{{ number_format($total-$total_pagos-$total_descuentos,0,",",".")}}</h4> 

                                        @else

                                            <h4 style="color:#1450C9;">{{ number_format($total-$total_pagos+$costo_envio-$total_descuentos,0,",",".")}}</h4> 

                                        @endif

                                </div>

                            </div>

                             

                           
                             @if($configuracion->mensaje_promocion!=null && $configuracion->mensaje_promocion!='')

                                 <div class="col-sm-12">

                                    <h4 style="color: #d5006e;">{{$configuracion->mensaje_promocion}} </h4>

                                </div>
                            

                            @endif

                            


                             <div class="col-sm-12" style="    border-top: 1px solid rgba(0,0,0,0.1);">

                                <div class="col-sm-8 col-xs-8" >
                                    <h4>Ahorro</h4>
                                </div>
                                <div class="col-sm-4 col-xs-4">

                                       <h4 style="color:#22b14c;">{{ number_format($total_base-$total+$total_descuentos,0,",",".")}}</h4> 

                                </div>

                            </div>

                        @endif

                        </div>

                    </div>



@if(count($descuentos)>0)

@else

<div class="col-sm-12">
    
    <div class=" text-center " style="margin-top: 1em;">

        <div class="col-sm-12 col-xs-12" style="padding: 1em;">

            <div  data-id="4" class="row forma border pointer cupones">

                <div class="col-sm-8 col-xs-12">

                   <p>Cupón de Descuento</p> 

                </div>

                <div class="col-sm-4 col-xs-12" style="padding:8px;background-color:#3c763d;color:#ffffff;">

                    <h5 class="text-center">Agregar <i class="fa fa-chevron-right"></i></h5>

                </div>

            </div>

        </div>
                               

    </div>


</div>
@endif

                                 


                                    




                                    <div class="clearfix">  </div>
                             

                                     <div class="resaviso"></div>
                                     @if (isset($aviso))

                                            @if($aviso=='')

                                            @else
         
                                            <div class="alert alert-danger">

                                                {{ $aviso }}

                                            </div>

                                            @endif

                                        @endif



                                