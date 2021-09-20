           <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

            <div class="row">
                
                <div class="col-sm-12 text-center">

                    @if(isset($almacen->nombre_almacen))
                
                        <h4 style="margin-top:0px !important;">Usted se Encuentra en el Almacén <strong>{{$almacen->nombre_almacen}}</strong></h4>

                        <h4 style="margin-top:0px !important;">{{$descripcion}}</h4>

                    @else

                        <h3>Debe Seleccionar un Almacen </h3>


                    @endif

                </div>

            </div>






@if(count($productos))

   
    @foreach($productos as $p)

        @if($p->tipo_producto=='1' || $p->tipo_producto=='3')

            @if(isset($cart['inventario'][$p->id]))

                @if($cart['inventario'][$p->id]>0)

                    <div class="col-sm-3">

                    <div class="row">
                            <img style="width: 100%;" src="{{secure_url('uploads/productos/250/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
                        </div>

                        <div class="row">
                            <p style="margin:0;"><b>{{$p->nombre_producto}}</b></p>
                            <p style="margin:0;" style="font-size: 0.8em; line-height: 1;">{{$p->nombre_categoria}}</p>
                            <p style="margin:0;">{{number_format($p->precio_oferta,0,',','.')}} COP</p>
                            <p style="margin:0;">
                                <button class="btn btn-primary addproducto"  data-id="{{$p->id}}" >Agregar</button>

                                <button class="btn btn-primary verProducto"  
                                data-id="{{$p->id}}" 
                                data-presentacion_producto="{{$p->presentacion_producto}}" 
                                data-referencia_producto="{{$p->referencia_producto}}" 
                                data-referencia_producto_sap="{{$p->referencia_producto_sap}}" 
                                data-nombre_categoria="{{$p->nombre_categoria}}" 
                                data-precio_base="{{$p->precio_base}}" 
                                data-precio_oferta="{{$p->precio_oferta}}" 
                                data-inventario="{{$cart['inventario'][$p->id]}}" 
                                >Detalle</button>

                            </p>
                        </div>

                    </div>

                @endif
                    
            @endif

        @else

            @if(isset($combos[$p->id]))

            <div class="col-sm-3">

                        <div class="row">
                            <img style="width: 100%;" src="{{secure_url('uploads/productos/250/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
                        </div>

                        <div class="row detalleproducto">
                            <p style="margin:0;"><b>{{$p->nombre_producto}}</b></p>
                            <p style="margin:0;" style="font-size: 0.8em; line-height: 1;">{{$p->nombre_categoria}} COP</p>
                            <p style="margin:0;">{{number_format($p->precio_oferta,0,',','.')}}</p>
                            <p style="margin:0;">
                                <button class="btn btn-primary addproducto"  data-id="{{$p->id}}" >Agregar</button>

                                <button class="btn btn-primary verProducto"  
                                data-id="{{$p->id}}" 
                                data-presentacion_producto="{{$p->presentacion_producto}}" 
                                data-referencia_producto="{{$p->referencia_producto}}" 
                                data-referencia_producto_sap="{{$p->referencia_producto_sap}}" 
                                data-nombre_categoria="{{$p->nombre_categoria}}" 
                                data-precio_base="{{$p->precio_base}}" 
                                data-precio_oferta="{{$p->precio_oferta}}" 
                                data-inventario="{{$cart['inventario'][$p->id]}}" 
                                >Detalle</button>

                            </p>
                        </div>

                    </div>

            
            @endif

        @endif
                
    @endforeach

            



@else

    <div class="alert alert-danger ">No hay productos disponibles</div>

@endif
















