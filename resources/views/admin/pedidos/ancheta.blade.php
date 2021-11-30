<div class="widget-body">

            <div class="row ">

            <div class="col-sm-12">
                
                <h1  class="text-primary tetx-center" id="titulo_single">Arma tu ancheta  </h1>

            </div>

            <div class="row">

                <div class="rightab">

                    <div class="tab-content">

                    @foreach($anchetas_categorias as $ac)

                    <div class="tab-pane @if($loop->index==0) active @endif  {{'tabpane'.$ac->id}}     " id="tab{{$loop->iteration}}" data-minima="{{$ac->cantidad_minima}}">

                        <br>
                        
                        <h3 style="margin-top: 1em;"><strong>Paso {{$loop->iteration}}  </strong> - 

                            @if($ac->cantidad_minima==0)

                                Seleccione {{$ac->nombre_categoria}} <small>*Productos opcionales</small></h3>

                            @else

                                Seleccione {{$ac->nombre_categoria}} <small>Debe seleccionar como minimo {{$ac->cantidad_minima}} productos </small></h3>

                            @endif

                            @foreach($ac->productos as $p)

                                @if(isset($inventario[$p->id]))

                                    @if($inventario[$p->id]>0)

                                        <div class="p{{$p->id}}">

                                            @include('admin.pedidos.pancheta')
                                            
                                        </div>


                                    @endif

                                @endif

                            @endforeach

                            <div class="clearfix"></div>

                            <div class="form-actions" style="margin-top: 1em;">
                                
                                <div class="row">
                                    
                                    <div class="col-sm-6 col-xs-6" style="text-align: left; padding: 0;">
                                        
                                        <ul class="pager1 wizard no-margin" style="padding: 0;">
                                            @if($loop->index==0)

                                                <!--li class="previous disabled">
                                                    <a href="#tab{{$loop->iteration}}" 
                                                       data-cantidad="-1"
                                                        class="btn  btn-danger btnnetx s{{$ac->id}}"
                                                        > Anterior </a>
                                                </li-->

                                            @else

                                                <li class="previous ">
                                                    <a 
                                                    href="#tab{{$loop->iteration-1}}" 
                                                    class="btn  btn-primary btnnetx s{{$ac->id}}"
                                                    data-cantidad="-1"
                                                    > Anterior </a>
                                                </li>

                                            @endif
                                                
                                                
                                            </ul>
                                        </div>


                                         <div class="col-sm-6 col-xs-6" style="text-align: right; padding: 0;">

                                            <ul class="pager1 wizard no-margin" style="padding: 0;">

                                                @if($loop->last)

                                                    <li class="next disabled">
                                                        <a 
                                                         data-id="{{$ac->id}}" 
                                                        data-cantidad="{{$ac->cantidad_minima}}"
                                                        class="btn  btn-primary finalizarAncheta "
                                                        > Finalizar Ancheta </a>
                                                    </li>


                                                @else

                                                     <li class="next">
                                                        <a 
                                                        data-id="{{$ac->id}}" 
                                                        href="#tab{{$loop->iteration+1}}" 
                                                        data-cantidad="{{$ac->cantidad_minima}}"
                                                        class="btn  btn-primary btnnetx s{{$ac->id}}"
                                                        > Siguiente </a>
                                                    </li>

                                                @endif
                                               
                                            </ul>

                                        </div>
                                    </div>
                                   

                                </div>

                            </div>
                                   
                        @endforeach

                    </div>

                </div>

            </div>

            <div class="row">
                                        
                <div class="col-sm-12">
                    
                    <div class="errorcantidad">

                    </div>

                </div>

            </div>


             <div class="row  " style="text-align: right;">

                <div class="col-sm-7">
    
                    <h3 style="text-align: left;">Formulario <small>Mensaje Personalizado para su ancheta</small></h3>

                    <form action="{{ secure_url('login') }}" class="omb_loginForm"  autocomplete="off" method="POST">

                        <div class="form-group {{ $errors->first('ancheta_de', 'has-error') }}">
                            <label class="sr-only">De:</label>
                            <input type="text" class="form-control" maxlength="50" name="ancheta_de" id="ancheta_de" placeholder="De"
                                   value="{!! old('ancheta_de') !!}">
                        </div>


                        <span class="help-block">{{ $errors->first('ancheta_de', ':message') }}</span>


                         <div class="form-group {{ $errors->first('ancheta_para', 'has-error') }}">
                            <label class="sr-only">Para: </label>
                            <input type="text" class="form-control" maxlength="50"  name="ancheta_para" id="ancheta_para" placeholder="Para"
                                   value="{!! old('ancheta_para') !!}">
                        </div>

                        
                        <span class="help-block">{{ $errors->first('ancheta_para', ':message') }}</span>


                         <div class="form-group {{ $errors->first('ancheta_mensaje', 'has-error') }}">
                            <label class="sr-only">Mensaje</label>

                            <textarea class="form-control" maxlength="250"  name="ancheta_mensaje" id="ancheta_mensaje" cols="5" rows="5" placeholder="Mensaje">{!! old('ancheta_mensaje') !!}</textarea>
                            
                        </div>

                        
                        <span class="help-block">{{ $errors->first('ancheta_mensaje', ':message') }}</span>


                       
                       
                    </form>
                </div>

                <div class="col-sm-5 listaancheta">

                    @include('frontend.listaancheta')
                    
                </div>
                                        
                

            </div>


        
        </div>