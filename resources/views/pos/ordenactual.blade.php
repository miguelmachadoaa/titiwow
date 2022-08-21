

             

            <div class="row m-0 p-0" style="height: 25em; overflow: auto;">   

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
                                    <h5 class="precio"> {{$p->precio_base}} x {{$p->cantidad}}</h5>
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

                                    <p class="m-0 text-right"> {{number_format($cart['total']-$cart['impuesto'],2)}}</p>

                            </div>   


                             <div class="col-sm-6"> 

                                <p class="m-0"> <b> Base: </b></p>

                            </div> 

                            <div class="col-sm-6">  

                                    <p class="m-0 text-right"> {{number_format($cart['base'],2)}}</p>

                            </div>   



                             <div class="col-sm-6"> 

                                <p class="m-0"> <b> Impuesto: </b></p>

                            </div> 

                            <div class="col-sm-6">  

                                    <p class="m-0 text-right"> {{number_format($cart['impuesto'],2)}}</p>

                            </div>   


                             <div class="col-sm-6"> 

                                <p class="m-0"> <b> Total: </b></p>

                            </div> 

                            <div class="col-sm-6 text-right">  

                                    <p class="m-0"> {{number_format($cart['total'],2)}}</p>

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
                            <button type="button" class="btn btn-success pagar w-100"> {{number_format($cart['total'],2)}}</button> </div>
                                @else
                            <button type="button" class="btn btn-outline-secondary w-100">0,00</button> </div>


                                @endif
                     </div>


            </div>  


