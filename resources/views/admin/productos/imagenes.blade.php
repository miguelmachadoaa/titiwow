 
@if(isset($imagenes))

     @foreach ($imagenes as $ima)

                  

                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                          <img src="{{URL::to('uploads/productos/'.$ima->imagen_producto)}}" alt="...">
                          <div class="caption">
                           
                            <p><button type="button" data-id="{{$ima->id}}" class="btn btn-danger delImagen">Eliminar</button></p>
                          </div>
                        </div>
                      </div>

                     

                @endforeach
    

@endif


