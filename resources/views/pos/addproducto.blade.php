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
                              <div class="form-group">
                                <label for="nombre_producto">Nombre del Producto</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" placeholder="Nombre del producto">
                              </div>

                              <div class="form-group">
                                <label for="precio">Precio <small> (Impuesto Incluido)</small></label>
                                <input type="text" class="form-control" id="precio" name="precio" placeholder="Precio del producto">
                              </div>

                              <div class="form-group">
                                <label for="exampleFormControlSelect1">Categoria</label>
                                <select class="form-control" id="id_categoria" name="id_categoria">
                                 @foreach($categorias as $c)
                                  <option value="{{$c->id}}">{{$c->nombre_categoria}}</option>
                                 @endforeach
                                </select>
                              </div>

                               <div class="form-group">
                                <label for="exampleFormControlSelect1">Impuesto</label>
                                <select class="form-control" id="id_impuesto" name="id_impuesto">
                                 @foreach($impuestos as $i)
                                  <option value="{{$i->id}}">{{$i->nombre_impuesto.' '.($i->valor_impuesto*100)}} %</option>
                                 @endforeach
                                </select>
                              </div>



                              <div class="form-group">
                                <button type="button" data-id="productos"  class="btn btn-danger cajita">Cancelar</button>
                                <button type="button" class="btn btn-primary saveproducto">Guardar</button>
                              </div>
                            </form>


                </div>  

               
            </div>