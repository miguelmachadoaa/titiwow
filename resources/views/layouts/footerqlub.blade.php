 <!-- Footer Section Start -->
 <footer class="footer_qlub" >
        <div class="container footer-text_qlub">
            <div class="row" style="margin:0px;padding:0px;">
                <!-- Categorias Section Start -->
                <div class="col-sm-3 clearfix">
                    <h4>Categorías </h4>
                    <p>
                        <ul id="menu-categorias" class="menu_footer">
                            <li class="menu-item"><a href="{{ secure_url('categoria/leche') }}">Leche</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/lacteos') }}">Derivados Lácteos</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/quesos') }}">Quesos</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/postres-dulces') }}">Postres y Dulces</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/esparcibles-ingredientes') }}">Esparcibles e Ingredientes</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/bebidas-frutas') }}">Bebidas de Fruta</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/finesse') }}">Linea Finesse</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/alpina-baby') }}">Alpina Baby</a></li>
                            <li class="menu-item"><a href="{{ secure_url('categoria/no-lacteos') }}">No Lácteos</a></li>
                        </ul>
                    </p>
                </div>
                <!-- //Categorias Section End -->
                 <!-- Marcas Section Start -->
                 <div class="col-sm-3 clearfix">
                   <h4>Contáctenos</h4>
                        <p>Cualquier inconveniente o duda, comunícate con nuestra línea de atención Alpina en Bogotá
                        
                        </p>
                        <p style="text-align:left !important;">Km 3, vía Briceño Sopó, Edificio Administrativo Alpina Cundinamarca, Colombia</p>
                        <p>(01) 8000529999</p>
                        <p>alpina@alpina.com</p>
                        <p><a href="{{ secure_url('pqr') }}">¿Necesitas ayuda? Contáctanos</a></p>
                    
                </div>
                <!-- Contacto Section Start -->
                <div class="col-sm-3 clearfix">
                    <img src="{{ secure_asset('uploads/files/pareycompare.png') }}" alt="Pare y Compare" title="Pare y Compare" class="img-responsive" style=" margin:10px auto;width:220px;">
                 </div>   
                <!-- Pare y Compare Section Start -->
                <div class="col-sm-3 clearfix">
                    
                    <a href="http://www.sic.gov.co" target="_blank"><img src="{{ secure_asset('uploads/files/logosic.png') }}" alt="Camara de Comercio Colombiana" title="Pare y Compare" class="img-responsive" style="margin:10px auto;width:160px;"></a>
                    <a href="https://www.ccce.org.co" target="_blank"><img src="{{ secure_asset('uploads/files/ccce.png') }}" alt="Secretaria de Industria y Comercio" title="Pare y Compare" class="img-responsive" style="margin:10px auto;width:160px;"></a>
                </div>
                <!-- //redes Section End -->

                </div>


            <!-- //Pare y Compare Section End -->
            <div class="row" style="margin:0px;padding:0px;">
                <div class="col-md-12 text-center">
                <div class="separador" style="    border-bottom: 2px solid #ffffff;margin-bottom: 10px; width:20%"></div>
                <a href="{{ secure_url('paginas/terminos-condiciones')}}" class="menu-item" target="_blank" alt="Términos y Condiciones de Acceso a Alpina Go" title="Términos y Condiciones de Acceso a Alpina Go">Términos y Condiciones de Acceso a Alpina Go</a> | <a href="{{ secure_asset('uploads/files/politica_de_tratamiento_de_la_informacion.PDF') }}" class="menu-item" target="_blank" title="Políticas de Tratamiento de la Información" alt="Políticas de Tratamiento de la Información">Políticas de Tratamiento de la Información</a>
                </div>
            </div>
            </div>
        </div>


    </footer>
    <!-- //Footer Section End -->
    <div class="copyright_qlub">
        <div class="container">
        <p>Todos los derechos reservados &copy; Alpina Productos Alimenticios S.A. NIT: 860025900-2, {{ date('Y') }} </p>
        </div>
    </div>
    <!--a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top" data-toggle="tooltip" data-placement="left">
        <i class="livicon" data-name="chevron-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
    </a-->
    




        <!-- Modal Direccion -->
    <div class="modal fade" id="ubicacionModal" role="dialog" aria-labelledby="modalLabeldanger" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Indícanos desde donde estas visitando nuestra tienda </h4>
                </div>
                
                <div class="modal-body ">
                     <form method="POST" action="{{secure_url('formasenvio/storeciudad')}}" id="addCiuadadForm" name="addCiuadadForm" class="form-horizontal">

                        <input type="hidden" name="base" id="base" value="{{ secure_url('/') }}">

                            <div class="form-group col-sm-12">
                                <label for="select21" class="col-md-3 control-label">
                                    Departamento
                                </label>
                                <div class="col-md-9" >
                                    <select id="state_id_ubicacion" name="state_id_ubicacion" class="form-control js-example-responsive" style="width: 100%">
                                        <option value="">Seleccione Departamento</option>       
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="select21" class="col-md-3 control-label">
                                    Ciudad
                                </label>
                                <div class="col-md-9" >
                                    <select id="city_id_ubicacion" name="city_id_ubicacion" class="form-control js-example-responsive" style="width: 100%">
                                        <option value="">Seleccione Ciudad</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="resciudad "></div>
                            </div>

                            
                        </form>     
                </div>
                <div class="modal-footer">
                    <!--button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button-->
                    <button type="button"   class="btn  btn-primary saveubicacion" >Aceptar</button>
                    
                </div>
            </div>
        </div>
    </div>

<!-- Modal Direccion -->
    @if(isset($almacen))
        <div class="almacen" data-id="{{$almacen->id}}"></div>
    @endif

     @if(isset($prods))
        <div class="prods" data-id="{{count($prods)}}"></div>
    @endif

    @if(isset($inventario))
        <div class="inventario" data-inventario="{{json_encode($inventario)}}"></div>
    @endif


   @include('layouts.jsfooter')


    <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->

    
</body>

</html>