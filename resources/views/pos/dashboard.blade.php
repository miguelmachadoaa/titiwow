<div class="row">

    <div class="col-sm-12 reserror"> 

         @if(!isset($caja->id))
            <div class="alert alert-danger mt-2"> Debe abrir caja para poder procesar una compra </div>
        @endif

    </div>
                
    <div class="col-sm-12 " style="margin-top: 1em;">

        <div class="row">

            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">
                <a data-id="dashboard" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-home"></i></div>
                    <div class="col-sm-12">Dashboard</div>
                    </div>
            

                </a>
            </div> 


            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 col-md-3 text-center">
                <a data-id="carritos" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" >
                        <i class=" mt-4 fa fa-shopping-cart"></i></div>
                    <div class="col-sm-12">Carritos</div>
                    </div>
            

                </a>
            </div>    
            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">
                <a data-id="pedidos" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-usd"></i></div>
                    <div class="col-sm-12">Pedidos </div>
                    </div>
                </a>
            </div> 
            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">   
                <a data-id="transacciones" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-shopping-cart"></i></div>
                    <div class="col-sm-12">Transacciones </div>
                    </div>
                </a>
            </div> 

            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="reportes" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-area-chart"></i></div>
                    <div class="col-sm-12">Reportes</div>
                    </div>
                </a>
            </div> 

            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a  data-id="categorias"  href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-tag"></i></div>
                    <div class="col-sm-12">Categorias</div>
                    </div>
                </a>
            </div> 

             <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="productos" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-table"></i></div>
                    <div class="col-sm-12">Productos</div>
                    </div>
                </a>
            </div> 

            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="clientes" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-users"></i></div>
                    <div class="col-sm-12">Clientes</div>
                    </div>
                </a>
            </div> 


             <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="opciones" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-cog"></i></div>
                    <div class="col-sm-12">Opciones</div>
                    </div>
                </a>
            </div> 

            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="caja" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-shopping-bag"></i></div>
                    <div class="col-sm-12">Caja</div>
                    </div>
                </a>
            </div>


            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="puntodeventa" href="#" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-shopping-bag"></i></div>
                    <div class="col-sm-12">Punto de Venta</div>
                    </div>
                </a>
            </div>


              <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="" href="#" class=" btn-medium verificarPinPad">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-shopping-bag"></i></div>
                    <div class="col-sm-12">Verificar PinPad</div>
                    </div>
                </a>
            </div>


            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="salir" target="_blank" href="{{secure_url('/admin')}}" class=" btn-medium ">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-cog"></i></div>
                    <div class="col-sm-12">Admin</div>
                    </div>
                </a>
            </div> 
         


            <div  class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a data-id="salir" href="{{secure_url('logout')}}" class=" btn-medium ">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class=" mt-4 fa fa-sign-out"></i></div>
                    <div class="col-sm-12">Salir</div>
                    </div>
                </a>
            </div> 
         
            
        </div>

        <div class="row">

            <div class="col-sm-12">

                <div class="resSitef" style="width: 100%;">
                
                </div>
                
            </div>

            
            
        </div> 



    </div>


    </div>


            