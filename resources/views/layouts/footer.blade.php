 <!-- Footer Section Start -->
 <footer>
        <div class="container footer-text">
            <!-- Mi cuenta Section Start -->
            <div class="col-sm-3 clearfix">
                <h4>Mi Cuenta</h4>
                <p>
                    <ul id="menu-principal-1" class="menu_footer"><li id="menu-item-8915" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-16 current_page_item menu-item-8915"><a href="http://www.centrodechapas.com.ar/">Inicio</a></li>
                        <li id="menu-item-9224" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9224"><a href="#">Empresa</a></li>
                        <li id="menu-item-9050" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9050"><a href="#">Productos</a></li>
                        <li id="menu-item-9174" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9174"><a href="#">Servicios</a></li>
                        <li id="menu-item-9051" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9051"><a href="#">Contacto</a></li>
                    </ul>
                </p>
            </div>
            <!-- //Mi cuenta Section End -->
            <!-- Categorias Section Start -->
            <div class="col-sm-3 clearfix">
                <h4>Categorías</h4>
                <p>
                    <ul id="menu-principal-1" class="menu_footer"><li id="menu-item-8915" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-16 current_page_item menu-item-8915"><a href="http://www.centrodechapas.com.ar/">Inicio</a></li>
                        <li id="menu-item-9224" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9224"><a href="#">Empresa</a></li>
                        <li id="menu-item-9050" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9050"><a href="#">Productos</a></li>
                        <li id="menu-item-9174" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9174"><a href="#">Servicios</a></li>
                        <li id="menu-item-9051" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9051"><a href="#">Contacto</a></li>
                    </ul>
                </p>
            </div>
            <!-- //Categorias Section End -->
            <!-- Redes Section Start -->
            <div class="col-sm-3 clearfix">
                <h4>SÍguenos en</h4>
                <p>
                <div class="redes_foot">
                    <div class="div_deres"><a href="https://www.facebook.com/alpina" target="_blank"><i class="fa fa-facebook-f color-foot" ></i></a></div>
                    <div class="div_deres"><a href="https://www.instagram.com/alpinacol/" target="_blank"><i class="fa fa-instagram color-foot" ></i></a></div>
                    <div class="div_deres"><a href="https://www.linkedin.com/company/alpina/?trk=vsrp_companies_res_name&trkInfo=VSRPsearchId%3A3286542181450727911739%2CVSRPtargetId%3A48174%2CVSRPcmpt%3Aprimary" target="_blank"><i class="fa fa-linkedin-square color-foot" ></i></a></div>
                    <div class="div_deres"><a href="https://twitter.com/Alpina" target="_blank"><i class="fa fa-twitter color-foot" ></i></a></div>
                    <div class="div_deres"><a href="https://www.youtube.com/user/AlpinaSA" target="_blank"><i class="fa fa-youtube-play color-foot" ></i></a></div>
                    <div style="clear: both;"></div>
                </div>
                </p>
                
            </div>
            <!-- //redes Section End -->
            <!-- Contacto Section Start -->
            <div class="col-sm-3 clearfix">
                <h4>Atención al Cliente</h4>
                    <div class="contacto_foot">
                        <div class="div_contacto1"><img src="{{ asset('assets/img/location.png') }}" border="0"></div>
                        <div class="div_contacto2">Bogotá, Colombia</div>
                        <div style="clear: both;"></div>
                        <div class="contacto_foot">
                        <div class="div_contacto1"><img src="{{ asset('assets/img/emails.png') }}" border="0"></div>
                        <div class="div_contacto2">pedidos@alpina.com</div>
                        <div style="clear: both;"></div>
                        <div class="contacto_foot">
                        <div class="div_contacto1"><img src="{{ asset('assets/img/phone.png') }}" border="0"></div>
                        <div class="div_contacto2">+57 0000000000</div>
                    </div>
                </div>
            </div>
            <!-- //Contacto Section End -->
            
        </div>
    </footer>
    <!-- //Footer Section End -->
    <div class="copyright">
        <div class="container">
        <p>Todos los derechos reservados &copy; Alpina, 2018</p>
        </div>
    </div>
    <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top" data-toggle="tooltip" data-placement="left">
        <i class="livicon" data-name="chevron-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
    </a>
    <!--global js starts-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/lib.js') }}"></script>
    <!--global js end-->
    <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->
</body>

</html>