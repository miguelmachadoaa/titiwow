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
    




        <!-- Modal Direccion -->
    <div class="modal fade" id="ubicacionModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Indicanos desde donde estas visitando nuestra tienda </h4>
                </div>
                
                <div class="modal-body ">
                     <form method="POST" action="{{url('formasenvio/storeciudad')}}" id="addCiuadadForm" name="addCiuadadForm" class="form-horizontal">

                        <input type="hidden" name="base" id="base" value="{{ url('/') }}">

                        <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Departamento
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="state_id_ubicacion" name="state_id_ubicacion" class="form-control ">
                                            <option value="">Seleccione</option>
                                                        
                                                        @foreach($states as $state)

                                                        <option value="{{ $state->id }}">
                                                                {{ $state->state_name}}</option>
                                                        @endforeach
                                                        
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="select21" class="col-md-3 control-label">
                                        Ciudad
                                    </label>
                                    <div class="col-md-8" >
                                        <select style="margin: 4px 0;" id="city_id_ubicacion" name="city_id_ubicacion" class="form-control ">
                                            <option value="">Seleccione</option>
                                           
                                            
                                          
                                        </select>
                                    </div>
                                </div>

                                 </form>


                        
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn  btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button"   class="btn  btn-primary saveubicacion" >Aceptar</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Direccion -->





    <!--global js starts-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/lib.js') }}"></script>
    <!--global js end-->

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>



    <!-- javascript para verificar y validar la ubicacion para la venta-->


    <script type="text/javascript">


        $(document).ready(function(){

             $('.addtocart').addClass('hidden');


            if (localStorage.getItem('ubicacion')) {

                ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                if (ubicacion.status=='true'){

                    $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                                $('.addtocart').removeClass('hidden');

                                


                }else{

                    $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                                

                                $('.addtocart').addClass('hidden');


                    $('#ubicacionModal').modal('show');

                }

            }else{

                $('#ubicacionModal').modal('show');
            }

        });

        $('#ubicacion_header').click(function(e){

            e.preventDefault();

            $('#ubicacionModal').modal('show');

        });

         $('.saveubicacion').click(function () {
    
            var $validator = $('#addCiuadadForm').data('bootstrapValidator').validate();

            if ($validator.isValid()) {

                base=$('#base').val();
                city_id=$('#city_id_ubicacion').val();

                $.ajax({
                    type: "POST",
                    data:{  city_id },
                    url: base+"/configuracion/verificarciudad",
                        
                    complete: function(datos){     

                            ubicacion=JSON.parse(datos.responseText);

                            localStorage.setItem('ubicacion', datos.responseText);

                             $('#ubicacionModal').modal('hide');


                             if (ubicacion.status=='true') {

                                $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                                $('.addtocart').removeClass('hidden');


                            }else{

                                $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                                $('.addtocart').addClass('hidden');
                            }

                    }
                });

                //document.getElementById("addDireccionForm").submit();

            }

        });
        

         $("#addCiuadadForm").bootstrapValidator({
            fields: {
                
                city_id_ubicacion: {
                    validators:{
                        notEmpty:{
                            message: 'Debe seleccionar una ciudad'
                        }
                    }
                }
            }
        });



         $('select[name="state_id_ubicacion"]').on('change', function() {
            
                var stateID = $(this).val();

                var base = $('#base').val();

                    if(stateID) {
                        $.ajax({
                            url: base+'/configuracion/cities/'+stateID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                
                                $('select[name="city_id_ubicacion"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="city_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');
                                });

                            }
                        });
                    }else{
                        $('select[name="city_id_ubicacion"]').empty();
                    }
                });

        </script>

    <!--javascript para verificar y validar la ubicacion para la venta -->












    <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->

    


    
</body>

</html>