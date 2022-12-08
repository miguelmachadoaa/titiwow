

             

            <div class="row m-0 p-0" style="height: 70vh; overflow: auto;">   

                <div class="col-sm-12"> 

                    @if(isset($cart['productos']))

                    <div data-id="{{json_encode($cart)}}">   </div>

                        @foreach($cart['productos'] as $p)

                            <div class="row my-2 orden-producto">   

                                <div class="col-sm-2 p-0" > 

                                        <img style="width: 100%" src="{{url('uploads/productos/'.$p->imagen_producto)}}" alt="">

                                </div>

                                <div class="col-sm-8 p-1">
                                    <p> {{$p->nombre_producto}}</p>
                                    <p> {{$p->referencia_producto}}</p>
                                    <h5 class="precio"> {{number_format($p->precio_base*$configuracion->tasa_dolar,2,'.',',')}} x {{$p->cantidad}}</h5>
                                </div>

                                <div class="col-sm-2">  

                                        <button data-id="{{$p->id}}" class="btn btn-danger deltocart"> <i class="fa fa-trash"> </i>   </button>

                                </div>  


                            </div>  

                        @endforeach

                    @endif


                </div>

            </div>  


            <div class="row">   

                        <div class="col-sm-6"> 

                            <p class="m-0"> <b> Subtotal: </b></p>

                        </div> 

                            <div class="col-sm-6">  

                                    <p class="m-0 text-right">Bs. {{number_format(($cart['total']-$cart['impuesto'])*$configuracion->tasa_dolar,2,',','.')}}</p>

                            </div>   


                             <div class="col-sm-6"> 

                                <p class="m-0"> <b> Base: </b></p>

                            </div> 

                            <div class="col-sm-6">  

                                    <p class="m-0 text-right">Bs. {{number_format($cart['base']*$configuracion->tasa_dolar,2,',','.')}}</p>

                            </div>   



                             <div class="col-sm-6"> 

                                <p class="m-0"> <b> Impuesto: </b></p>

                            </div> 

                            <div class="col-sm-6">  

                                    <p class="m-0 text-right">Bs. {{number_format($cart['impuesto']*$configuracion->tasa_dolar,2,',','.')}}</p>

                            </div>   


                             <div class="col-sm-6"> 

                                <p class="m-0"> <b> Total Usd: </b></p>

                            </div> 

                            <div class="col-sm-6 text-right">  

                                    <p class="m-0">Usd. {{number_format($cart['total'], 2, ',', '.')}}</p>

                            </div>  


                              <div class="col-sm-6"> 

                                <p class="m-0"> <b> Total Bs.: </b></p>

                            </div> 

                            <div class="col-sm-6 text-right">  
                                    @if(isset($cart['total_bs']))
                                    <p class="m-0">
                                    Bs. {{number_format($cart['total_bs'], 2, ',', '.')}}
                                    </p>
                                    @else
                                    <p class="m-0">
                                     {{number_format(0, 2, ',', '.')}}
                                    </p>
                                    @endif

                            </div>  

                        </div> 


            <div class="row">   


                    <div class="col-sm-2"> 
                            <button class="btn btn-warning vaciarcarrito">    <i class="fa fa-trash">  </i></button> 
                    </div>

                    <div class="col-sm-2">
                            <button class="btn btn-info guardarcarrito">    <i class="fa fa-save">  </i></button> 
                        </div>

                    <div class="col-sm-8 "> 
                                @if($cart['total']>0)
                            <button type="button" class="btn btn-success pagar w-100">Bs. {{number_format($cart['total']*$configuracion->tasa_dolar,2,',','.')}}</button> </div>
                                @else
                            <button type="button" class="btn btn-outline-secondary w-100">Bs. 0,00</button> </div>


                                @endif
                     </div>


            </div>  


