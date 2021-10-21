           <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}" />

            <div class="row">
                
                <div class="col-sm-12 text-center">

                    @if(isset($almacen->nombre_almacen))
                
                        <h4 style="margin-top:0px !important;">Usted se Encuentra en el Almacén <strong>{{$almacen->nombre_almacen}}</strong></h4>

                        <h4 style="margin-top:0px !important;">{{$descripcion}}</h4>

                    @else

                        <h3>No hay Almacen de despacho para la dirección de envio seleccionada   </h3>


                    @endif

                </div>

            </div>




@if(isset($cart['id_cliente']))

    @if(count($productos))
    
        @foreach($productos as $p)

            @if($p->tipo_producto=='1' || $p->tipo_producto=='3')

                @if(isset($cart['inventario'][$p->id]))


                        <div class="col-sm-4 col-md-3 col-xs-6" style="display: flex;    flex-direction: column;">

                        <div class="row" style="flex-grow: 1; ">
                                <img style="width: 100%;" src="{{secure_url('uploads/productos/250/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
                                
                                @if($cart['inventario'][$p->id]<=0)

                                    <img class="agotado" style="" src="{{ secure_url('/').'/uploads/files/agotado.png' }}" alt="Agotado" title="Agotado">

                                @endif
                            </div>

                            <div class="row" style="flex-grow: 1;">
                                <p style="margin:0; height: 3em;"><b>{{$p->nombre_producto}}</b></p>
                                <p style="margin:0;" style="font-size: 0.8em; line-height: 1;">{{$p->nombre_categoria}}</p>
                                <p style="margin:0;"> <b>Precio: {{number_format($p->precio_oferta,0,',','.')}} COP</b> </p>
                                <p style="margin:0;"> <b>Inventario: {{$cart['inventario'][$p->id]}}</b> </p>

                                <p style="margin:0; display:flex; flex-grow:0.5; align-content:stretch " class="">
                                    <button style="flex-grow: 1;" class="btn btn-success addproducto"  data-id="{{$p->id}}" ><img  style="width: 32px; " src="{{secure_url('assets/images/carrito.png')}}" alt=""></button>

                                    <button style="flex-grow: 1;" class="btn btn-primary verProducto"  
                                    data-id="{{$p->id}}" 
                                    data-nombre_producto="{{$p->nombre_producto}}" 
                                    data-presentacion_producto="{{$p->presentacion_producto}}" 
                                    data-referencia_producto="{{$p->referencia_producto}}" 
                                    data-referencia_producto_sap="{{$p->referencia_producto_sap}}" 
                                    data-nombre_categoria="{{$p->nombre_categoria}}" 
                                    data-precio_base="{{number_format($p->precio_base,2,',','.')}}" 
                                    data-precio_oferta="{{number_format($p->precio_oferta,2,',','.')}}" 
                                    data-inventario="{{$cart['inventario'][$p->id]}}" 
                                    data-imagen="{{secure_url('uploads/productos/250/'.$p->imagen_producto)}}" 
                                    ><img style="width: 32px; " src="{{secure_url('assets/images/visible.png')}}" alt="">  </button>

                                </p>
                            </div>

                        </div>

                        
                @endif

            @else

            

                @if(isset($combos[$p->id]))

                <div class="col-sm-4 col-md-3    col-xs-6">

                <div class="row" style="flex-grow: 1; ">
                                <img style="width: 100%;" src="{{secure_url('uploads/productos/250/'.$p->imagen_producto)}}" alt="{{$p->nombre_producto}}"></td>
                            </div>

                            <div class="row detalleproducto"  style="flex-grow: 1;">
                                <p style="margin:0; height: 3em;"><b>{{$p->nombre_producto}}</b></p>
                                <p style="margin:0;" style="font-size: 0.8em; line-height: 1;">{{$p->nombre_categoria}} COP</p>
                                <p style="margin:0;"> <b>Precio: {{number_format($p->precio_oferta,0,',','.')}} COP</b> </p>
                                <p style="margin:0;"> <b>Inventario: {{$cart['inventario'][$p->id]}}</b> </p>
                                
                                <p style="margin:0; display:flex; flex-grow:0.5; align-content:stretch">
                                    <button style="flex-grow: 1;" class="btn btn-success addproducto"  data-id="{{$p->id}}" ><img  style="width: 32px; " src="{{secure_url('assets/images/carrito.png')}}" alt=""></button>

                                    <button style="flex-grow: 1;" class="btn btn-primary verProducto"  
                                    data-id="{{$p->id}}" 
                                    data-nombre_producto="{{$p->nombre_producto}}" 
                                    data-presentacion_producto="{{$p->presentacion_producto}}" 
                                    data-referencia_producto="{{$p->referencia_producto}}" 
                                    data-referencia_producto_sap="{{$p->referencia_producto_sap}}" 
                                    data-nombre_categoria="{{$p->nombre_categoria}}" 
                                    data-precio_base="{{number_format($p->precio_base,2,',','.')}}" 
                                    data-precio_oferta="{{number_format($p->precio_oferta,2,',','.')}}" 
                                    data-inventario="{{$cart['inventario'][$p->id]}}" 
                                    data-imagen="{{secure_url('uploads/productos/250/'.$p->imagen_producto)}}" 
                                    ><img  style="width: 32px; " src="{{secure_url('assets/images/visible.png')}}" alt=""></button>

                                </p>
                            </div>

                        </div>

                
                @endif

            @endif
                    
        @endforeach


    @else

        <div class="alert alert-danger ">No hay productos disponibles</div>

    @endif

@else

<div class="alert alert-danger ">Debe Seleccionar Un Cliente para ver el Catalogo de productos </div>

@endif














