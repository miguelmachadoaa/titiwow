<div class="row productoscarrito">

    @if(is_array($cart))

    <div class="col-xs-12 hidden-xs" >

<div class="row "  style="padding:0; margin:0;     border-bottom: 2px solid rgba(0,0,0,0.1);">
    
    <div class="col-sm-2" style="padding-top: 3%;">
    </div>
    <div class="col-sm-4" style="padding-top: 3%;">
        <b>Producto</b>   
    </div>
    
    <div class="col-sm-2 col-xs-4" style="padding-top: 3%;">
    <b>P/U</b>          
    </div>

    <div class="col-sm-1 col-xs-1" style="padding-top: 3%;">
      <b>Cant.</b>  
    </div>


    <div class="col-sm-2 col-xs-4" style="padding-top: 3%; ">
     <b>Total</b> 
    </div>

    <div class="col-sm-1 col-xs-2" style="padding-left:0; padding-right:0; padding-top: 3%;     text-align: right; ">
       
    </div>

</div>

</div>

        @foreach($cart as $key=>$cr)

        <div class="col-xs-12 " >

            <div class="row productoscarritodetalle"  style="padding:0; margin:0;     border-bottom: 2px solid rgba(0,0,0,0.1);">
                
                <div class="col-sm-2" style="padding-top: 3%;">
                    <img style="width:100% ; max-width: 90px;" src="{{secure_url('uploads/productos/'.$cr->imagen_producto)}}"  alt="{{$cr->nombre_producto}}">
                </div>
                <div class="col-sm-4" style="padding-top: 3%;">
                    <p class="visible-xs" style="margin:0"><small><b>Producto</b></small></p>
                    <p>{{$cr->nombre_producto}}</p>
                </div>
                
                <div class="col-sm-2 col-xs-4" style="padding-top: 3%;">
                <p class="visible-xs" style="margin:0"><small><b>P/U</b>      </small></p>
                    <p>${{number_format($cr->precio_oferta, 0, ',', '.')}} </p>
                </div>

                <div class="col-sm-1 col-xs-1" style="padding-top: 3%;">
                <p class="visible-xs" style="margin:0"><small><b>Cant</b></small></p>
                    <p>{{$cr->cantidad}} </p>
                </div>


                <div class="col-sm-2 col-xs-4" style="padding-top: 3%; ">
                <p class="visible-xs" style="margin:0"><small><b>Total</b></small></p>
                    <p>${{number_format($cr->precio_oferta*$cr->cantidad, 0, ',', '.')}} </p>
                </div>

                <div class="col-sm-1 col-xs-2" style="padding-left:0; padding-right:0; padding-top: 3%;     text-align: right; ">
                    <a data-id="{{ $cr->slug}}" data-slug="{{ $cr->slug}}"  href="#0" class="delete-item btn btn-danger">
                    <i class="fa fa-trash"></i>
                </a>
                </div>

            </div>

        </div>

        @endforeach

    @endif


</div>



<div class="row totalcarrito" style="margin: 1em;    background: #eee;"> 
    <div class="col-xs-9"><b>Subtotal</b></div>

    <div class="col-xs-3">${{number_format($total, 0, ',', '.')}} </div>

    <div class="col-xs-9"><b> Envio </b></div>
    
    @if(isset($envio))

        @if($envio==0)

            <div class="col-xs-3 envio1">Gratis </div>

        @else
            <div class="col-xs-3 envio2">${{number_format($envio, 0, ',', '.')}} </div>

        @endif


    @else
    <div class="col-xs-3 envi03">Gratis </div>

    @endif

    

    <div class="col-xs-9"><b>Total</b></div>
    @if(isset($envio))
      
        <div class="col-xs-3">${{number_format($total+$envio, 0, ',', '.')}} </div>
        
    @else
    <div class="col-xs-3">${{number_format($total, 0, ',', '.')}} </div>
    @endif


</div>

<div class="row">
    <div class="col-xs-6">
        <button data-dismiss="modal" aria-label="Close" style="width:100%" class="btn btn-success" >Seguir Comprando </button>
    </div>
    <div class="col-xs-6">
        <a style="width:100%" href="{{secure_url('cart/show')}}" class="btn btn-primary" >Ir a pagar </a>
        
    </div>
</div>