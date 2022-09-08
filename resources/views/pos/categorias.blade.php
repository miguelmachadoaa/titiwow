<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                           <input class="form-control" type="text" name="termino" id="termino" value="" placeholder="Buscar">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary buscarproducto"><i class="fa fa-search"></i></button>
                            <button data-id="addcategoria" class="btn btn-primary cajita"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12"> 
                    <div class="row" style="padding: 1em;">   


                         @foreach($categorias as $c)

                            <div class="col-sm-4 ">   

                                <div  class="row categoria cajasombra detallecategoria m-1" data-id="{{$c->id}}" style="min-height: 7em;">   

                                    <div class="col-sm-4 p-0" > 

                                        @if($c->imagen_categoria=='0')

                                            <img style="width: 100%" src="{{url('uploads/categorias/default.png')}}" alt="">

                                        @else

                                            <img style="width: 100%" src="{{url('uploads/categorias/'.$c->imagen_categoria)}}" alt="">

                                        @endif

                                        

                                     </div>
                                    <div class="col-sm-8">
                                        <h6> {{$c->nombre_categoria}}</h6>
                                    </div>

                                </div>  

                           </div>   

                        @endforeach

                    </div>

                       


                </div>  

               
            </div>