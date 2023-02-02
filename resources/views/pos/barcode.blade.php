<div class="col-sm-8">
    <input tabindex="0" class="form-control" type="text" id="barcode" name="barcode" value="" placeholder="Barcode / Nombre / Sku">
</div>

<div class="col-sm-4 ">

    <div class="row">
        
        <div class="col-sm-10">

            @if(isset($cart['cliente']->id))


                 <input tabindex="1" class="form-control" type="text" name="cliente" id="cliente" value="{{$cart['cliente']->first_name.' '.$cart['cliente']->lat_name}}" disabled="" placeholder="Buscar Cliente">   


            @else

                <input tabindex="1"  class="form-control" type="text" name="cliente" id="cliente" value="" placeholder="Buscar Cliente">

            @endif


        </div>

        <div class="col-sm-2">

            @if(isset($cart['cliente']->id))

                <button class="btn btn-danger removecliente"><i class="fa fa-remove"></i></button>

            @else

                <button class="btn btn-primary buscarcliente"><i class="fa fa-user"></i></button>

            @endif

        </div>

    </div>
    
</div>

                


