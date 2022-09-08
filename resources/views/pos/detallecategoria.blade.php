<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="categorias" class=" btn btn-primary cajita" href="#"><i class="fa fa-mail-reply"></i></a>
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                           <input class="form-control" type="text" name="termino" id="termino" value="" placeholder="Buscar">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary buscarproducto"><i class="fa fa-search"></i></button>
                            <button data-id="addproducto" class="btn btn-primary cajita"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12"> 
                    <div class="row" style="padding: 1em;">   


                         @foreach($productos as $p)

                         @if($p->precio_base>0)

                            <div class="col-sm-4 ">   

                                <div class="row producto" data-id="{{$p->id}}">   

                                    <div class="col-sm-4 p-0" > 

                                        <img style="width: 100%" src="{{url('uploads/productos/'.$p->imagen_producto)}}" alt="">

                                        <span class="existencia">  {{$p->id}}</span>

                                     </div>
                                    <div class="col-sm-8">
                                        <p> {{$p->nombre_producto}}</p>
                                        <p> {{$p->referencia_producto}}</p>
                                        <h5 class="precio"> {{$p->precio_base}} </h5>
                                    </div>

                                </div>  

                           </div>   

                        @endif
                        
                        @endforeach

                    </div>

                       


                </div>  

               
            </div>