<div class="row">

        <div class="col-sm-12 " style="margin-top: 1em;">
            <div class="row">
                
                <div class="col-sm-2">
                    <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                </div>
                <div class="col-sm-8">
                   <p>Carritos</p>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>


            <div class="row">   

                <div class="col-sm-12"> 

                    @foreach($carritos as $c)

                    <div class="row cajasombra my-2" >  

                            <div class="col-sm-10">

                                    <p> {{$c->referencia}} / {{$c->created_at}} / {{count($c->detalles)}} Productos</p>

                            </div>
                            <div class="col-sm-2"> 
                                    <button data-id="{{$c->id}}" class="btn btn-danger delcarrito"> <i class="fa fa-trash"> </i>   </button>
                                    <button data-id="{{$c->id}}"  class="btn btn-primary setcarrito"> <i class="fa fa-arrow-right">    </i>   </button>
                            </div>

                    </div>


                    @endforeach

                </div>

                

            </div>  
          
        </div>

       
    </div>

