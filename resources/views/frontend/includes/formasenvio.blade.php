   <div class="row">

            @if(count($formasenvio))

            <div class=" col-sm-12 col-xs-12">

                <h3>    Formas de Envios</h3>

                <!--input type="hidden" id="id_forma_envio" name="id_forma_envio"  value="1" -->

                <select class="form-control" name="id_forma_envio" id="id_forma_envio">


                    @foreach($formasenvio as $fe)

                    <option  value="{{$fe->id}}"   @if($fe->id==$id_forma_envio) {{'Selected'}} @endif      >{{$fe->nombre_forma_envios}}</option>

                    @endforeach

                </select>

                <!--div class="col-sm-12" style="    border: 1px solid rgba(0,0,0,0.1);">



                    <div class="col-sm-12 col-xs-12" >

                        <h4>{{ $formasenvio[0]['descripcion_forma_envios']}}</h4>
                    
                    </div>

                </div-->

            </div> <!-- End form group -->
            <div class="col-sm-12">

            <h6 style="text-align: justify;">* Los pedidos que se realicen de lunes a viernes, entre las 8:00 am y 5:00 pm serán entregados al siguiente día; por su parte, los pedidos que se realicen después de las 5:00pm serán entregados dos (2) días después. Aquellos que se realicen los sábados antes de las 3:00 pm serán entregados el lunes siguiente y los que se hagan los sábados después de las 3:00 pm, domingos y lunes antes de las 7:00 am serán entregados el martes.</h6>

            </div>  

            @else

            <div class="col-sm-12">

                <h3>No hay Formas de envios para este grupo de usuarios</h3>

            </div> 

            @endif

            <!-- End formas de pago -->

    </div>