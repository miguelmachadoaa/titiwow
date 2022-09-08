<div class="row">

                <div class="col-sm-12 mb-2" style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control"  type="text">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary buscarcliente"><i class="fa fa-search"></i></button>
                            <button class="btn btn-info addcliente"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                  
                </div>

                 <div class="col-sm-12 reserror"> 

         @if(!isset($caja->id))
            <div class="alert alert-danger mt-2"> Debe abrir caja para poder procesar una compra </div>
        @endif

    </div>

    

                <div class="col-sm-12">
                        
                    <form>
                              <div class="form-group">
                                <label for="nombre_cliente">Nombre Cliente</label>
                                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" placeholder="Nombre Cliente">
                              </div>

                              <div class="form-group">
                                <label for="cedula_cliente">Cedula Cliente</label>
                                <input type="number" class="form-control" id="cedula_cliente" name="cedula_cliente" placeholder="Cedula Cliente">
                              </div>

                              <div class="form-group">
                                <label for="telefono_cliente">Télefono Cliente</label>
                                <input type="text" class="form-control" id="telefono_cliente" name="telefono_cliente" placeholder="Telefono Cliente">
                              </div>


                              <div class="form-group">
                                <label for="email_cliente">Email Cliente</label>
                                <input type="email" class="form-control" id="email_cliente" name="email_cliente" placeholder="Email Cliente">
                              </div>



                              <div class="form-group">
                                <button type="button" data-id="clientes"  class="btn btn-danger cajita">Cancelar</button>
                                <button type="button" class="btn btn-primary savecliente">Guardar</button>
                              </div>

                    </form>
                    

                </div>

               
            </div>