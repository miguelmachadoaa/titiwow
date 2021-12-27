<div class="row">
<div class="col-md-3 hidden-xs hidden-sm" style="padding-right:30px">
@include('layouts.sidebar')
</div>
<div class="col-md-9">

    @if(isset($banner->id))


      <div class="row hidden-xs" >
            <div class="col-sm-12" style="margin-top:20px">
                <a target="_blank" href="{{$banner->enlace_categoria}}"><img style="width: 100%;"  src="{{secure_url('/assets/images/'.$banner->banner_categoria)}}" alt=""></a>
            </div>
        </div>

        <div class="row visible-xs" >
            <div class="col-sm-12" style="margin-top:20px">
               <a  target="_blank" href="{{$banner->enlace_categoria}}"><img  style="width: 100%;" src="{{secure_url('/assets/images/'.$banner->banner_movil_categoria)}}" alt=""></a> 
            </div>
        </div>



    @else


    @endif



    <div class="products">
        <div class="row">
        @if(count($productos)>0)

        @php $i=0; @endphp

            @foreach($prods as $producto)

               @if($producto->tipo_producto=='1' || $producto->tipo_producto=='3' || $producto->tipo_producto=='4')

                    @if($configuracion->mostrar_agotados=='1')
                    
                            @php $i++; @endphp

                            @if(isset($inventario[$producto->id]))

                            @else

                            @php  $inventario[$producto->id]=0; @endphp

                            @endif

                            @include('frontend.producto')


                            @if ($i % 4 == 0)
                                </div>
                                <div class="row">
                            @endif

                    @else

                            @if(isset($inventario[$producto->id]))

                                @if($inventario[$producto->id]>0)

                                    @php $i++; @endphp

                                    @include('frontend.producto')


                                        @if ($i % 4 == 0)
                                            </div>
                                            <div class="row">
                                        @endif

                                @endif

                            @endif



                    @endif
                   
                @else <!-- Si es combo -->

                    @if($configuracion->mostrar_agotados=='1')

                        @if(!isset($combos[$producto->id]) )

                            @php $combos[$producto->id]=0; @endphp

                        @endif


                        @if($producto->precio_oferta>0)

                            @php $i++; @endphp

                            @include('frontend.producto')


                                @if ($i % 4 == 0)
                                    </div>
                                    <div class="row">
                                @endif
                        @endif

                        @else

                        @if(isset($combos[$producto->id]) )

                            @if($combos[$producto->id] )

                                @if($producto->precio_oferta>0)

                                    @php $i++; @endphp

                                    @include('frontend.producto')


                                        @if ($i % 4 == 0)
                                            </div>
                                            <div class="row">
                                        @endif
                                @endif

                            @endif

                        @endif


                        @endif

                @endif


            @endforeach
            @else
            <div class="alert alert-danger">
                <strong>Lo Sentimos!</strong> No Existen productos relacionados con su Búsqueda.
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1 class="subtitulo" style="font-size: 24px; color: #241F48; margin-bottom: 15px; font-weight: 500; font-family: 'AlpinaSans Semibold';">   Intente búscar Nuevamente</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <form method="GET" action="{{ secure_url('buscar') }}">
                        <div class="row">
                            <div class="col-sm-12  col-xs-12 col-lg-8">
                                <div class="input-group"> 
                                    <input type="text" name="buscar"  id="buscar" class="form-control" placeholder="Buscar ..." value="{{ old('buscar') }}" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default busqueda" alt="Buscar" ><i class="fa fa-search" aria-hidden="true" id="busqueda"></i></button>
                                    </span>
                                </div><!-- /input-group -->
                            </div><!-- /.col-lg-6 -->
                        </div>  
                    </form>
                </div>
            </div>
        @endif
        </div>
          @include('frontend.includes.paginador')
    </div>
</div>
</div>
</div>
