 
@if(isset($imagenes))

  @foreach ($imagenes as $ima)

    <div class="row imagenadicional" >
      <div class="col-xs-4">
        <div class="thumbnail">
          <img src="{{URL::to('uploads/productos/'.$ima->imagen_producto)}}" alt="...">
         
        </div>
      </div>

      <div class="col-xs-8 imagenadicional__formulario">

        <div class="form-group clearfix">
            <label class="col-xs-2 control-label imagenadicional__formulario--label" for="title_imagen_{{$ima->id}}">Title</label>
            <div class="col-xs-10">
                <input id="title_imagen_{{$ima->id}}" name="title_imagen_{{$ima->id}}" type="text" placeholder="Title" class="form-control" value="{{ old('enlace_youtube', $ima->title) }}">
            </div>
        </div>

        <div class="form-group clearfix">
            <label class="col-xs-2 control-label imagenadicional__formulario--label" for="alt_imagen_{{$ima->id}}">Alt</label>
            <div class="col-xs-10">
                <input id="alt_imagen_{{$ima->id}}" name="alt_imagen_{{$ima->id}}" type="text" placeholder="Alt" class="form-control" value="{{ old('enlace_youtube', $ima->alt) }}">
            </div>
        </div>


        <p>
        <button type="button" data-id="{{$ima->id}}" class="btn btn-info updateImagen">Actualizar</button>

        <button type="button" data-id="{{$ima->id}}" class="btn btn-danger delImagen">Eliminar</button>
        </p>
      </div>
      
    </div>

  @endforeach

@endif


