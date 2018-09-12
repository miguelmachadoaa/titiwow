 <!-- Footer Section Start -->
 <footer>
        <div class="container footer-text">
            <!-- Mi cuenta Section Start -->
            <div class="col-sm-3">
                <h4>Mi Cuenta</h4>
                <p>
                    <ul id="menu-principal-1" class="menu_footer"><li id="menu-item-8915" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-16 current_page_item menu-item-8915"><a href="http://www.centrodechapas.com.ar/">Inicio</a></li>
                        <li id="menu-item-9224" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9224"><a href="http://www.centrodechapas.com.ar/empresa/">Empresa</a></li>
                        <li id="menu-item-9050" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9050"><a href="http://www.centrodechapas.com.ar/productos/">Productos</a></li>
                        <li id="menu-item-9174" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9174"><a href="http://www.centrodechapas.com.ar/servicios/">Servicios</a></li>
                        <li id="menu-item-9051" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9051"><a href="http://www.centrodechapas.com.ar/contacto/">Contacto</a></li>
                    </ul>
                </p>
            </div>
            <!-- //Mi cuenta Section End -->
            <!-- Categorias Section Start -->
            <div class="col-sm-3">
                <h4>Categorías</h4>
                <p>
                    <ul id="menu-principal-1" class="menu_footer"><li id="menu-item-8915" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-16 current_page_item menu-item-8915"><a href="http://www.centrodechapas.com.ar/">Inicio</a></li>
                        <li id="menu-item-9224" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9224"><a href="http://www.centrodechapas.com.ar/empresa/">Empresa</a></li>
                        <li id="menu-item-9050" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9050"><a href="http://www.centrodechapas.com.ar/productos/">Productos</a></li>
                        <li id="menu-item-9174" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9174"><a href="http://www.centrodechapas.com.ar/servicios/">Servicios</a></li>
                        <li id="menu-item-9051" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9051"><a href="http://www.centrodechapas.com.ar/contacto/">Contacto</a></li>
                    </ul>
                </p>
            </div>
            <!-- //Categorias Section End -->
            <!-- Redes Section Start -->
                        <div class="col-sm-3">
                <h4>Mi Cuenta</h4>
                <p>
                <div class="redes_foot">
                    <div class="div_deres"><a title="Síguenos en Facebook" href="https://www.facebook.com/" target="_blank" rel="noopener"><img src="{{ asset('assets/img/facebook.png') }}" border="0"></a></div>
                    <div class="div_deres"><a title="Síguenos en Instagram" href="https://www.instagram.com/" target="_blank" rel="noopener"><img src="{{ asset('assets/img/instagram.png') }}" border="0"></a></div>
                    <div style="clear: both;"></div>
                    <div class="div_deres"><a title="Síguenos en Twitter" href="https://www.facebook.com/" target="_blank" rel="noopener"><img src="{{ asset('assets/img/twitter.png') }}" border="0"></a></div>
                    <div class="div_deres"><a title="Síguenos en Youtube" href="https://www.instagram.com/" target="_blank" rel="noopener"><img src="{{ asset('assets/img/youtube.png') }}" border="0"></a></div>
                </div>
                </p>
                
            </div>
            <!-- //redes Section End -->
            <!-- Contacto Section Start -->
            <div class="col-sm-3">
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