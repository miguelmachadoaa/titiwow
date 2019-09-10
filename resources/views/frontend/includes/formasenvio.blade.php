   <div class="row">

            @if(count($formasenvio))

            <div class=" col-sm-12 col-xs-12">

                <h3>    Formas de Envios</h3>

                <input type="hidden" id="id_forma_envio" name="id_forma_envio"  value="{{$id_forma_envio}}">

                <!--select class="form-control" name="id_forma_envio" id="id_forma_envio"-->


                    @foreach($formasenvio as $fe)

                    <!--option  value="{{$fe->id}}"   @if($fe->id==$id_forma_envio) {{'Selected'}} @endif      >{{$fe->nombre_forma_envios}}</option-->

                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="formaenvio" id="formaenvio{{$fe->id}}" value="{{$fe->id}}"  @if($fe->id==$id_forma_envio) {{'checked'}} @endif  >
                      <label class="form-check-label" for="exampleRadios1">
                        {{$fe->nombre_forma_envios}}
                      </label>
                    </div>

                    <h6 style="text-align: justify;">{{$fe->descripcion_forma_envios}}</h6>



                    @endforeach

                <!--/select-->

                <!--div class="col-sm-12" style="    border: 1px solid rgba(0,0,0,0.1);">



                    <div class="col-sm-12 col-xs-12" >

                        <h4>{{ $formasenvio[0]['descripcion_forma_envios']}}</h4>
                    
                    </div>

                </div-->

            </div> <!-- End form group -->
            <div class="col-sm-12">

            

            </div>  

            @else

            <div class="col-sm-12">

                <h3>No hay Formas de envios para este grupo de usuarios</h3>

            </div> 

            @endif

            <!-- End formas de pago -->

    </div>