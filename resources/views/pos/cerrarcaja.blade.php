<div class="row">

                <div class="col-sm-12 " style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                           <input class="form-control" type="text" name="termino" id="termino" value="" placeholder="Buscar">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                            <button data-id="addproducto" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>


                <div class="col-sm-12"> 

                        <form>

                            <input type="hidden" id="idcaja" name="idcaja" value="{{$caja->id}}" >
                              

                              <div class="form-group">
                                <label for="precio">Monto Cierre </label>
                                <input type="text" class="form-control" id="basefinal" name="basefinal" placeholder="Monto Cierre">
                              </div>

                            <div class="form-group">
                                <label for="precio">Notas </label>
                                <textarea class="form-control" id="observacion" name="observacion">{{$caja->observacion}}</textarea>
                              </div>




                              <div class="form-group">
                                <button type="button" data-id="dahboard"  class="btn btn-danger cajita">Cancelar</button>
                                <button type="button" class="btn btn-primary updatecaja">Guardar</button>
                              </div>
                            </form>


                </div>  

               
            </div>