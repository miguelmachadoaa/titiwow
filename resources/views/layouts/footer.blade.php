 <!-- Footer Section Start -->
 <footer>
        <div class="container footer-text">
            <div >
                <!-- Mi cuenta Section Start -->
                <div class="col-sm-3 clearfix">
                    <h4>Mi Cuenta</h4>
                    <p>
                        <ul id="menu-cuenta" class="menu_footer">
                            <li class="menu-item"><a href="#">Mi Cuenta</a></li>
                            <li class="menu-item"><a href="#">Mis Compras</a></li>
                            <li class="menu-item"><a href="#">Mi Dirección</a></li>
                            <li class="menu-item"><a href="#">Mi Carrito</a></li>
                        </ul>
                    </p>
                </div>
                <!-- //Mi cuenta Section End -->
                <!-- Categorias Section Start -->
                <div class="col-sm-3 clearfix">
                    <h4>Categorías</h4>
                    <p>
                        <ul id="menu-categorias" class="menu_footer">
                            <li class="menu-item"><a href="#">Leche</a></li>
                            <li class="menu-item"><a href="#">Lácteos</a></li>
                            <li class="menu-item"><a href="#">Quesos</a></li>
                            <li class="menu-item"><a href="#">Postres y Dulces</a></li>
                            <li class="menu-item"><a href="#">Esparcibles e Ingredientes</a></li>
                            <li class="menu-item"><a href="#">Bebidas de Fruta</a></li>
                            <li class="menu-item"><a href="#">Linea Finesse</a></li>
                            <li class="menu-item"><a href="#">Alpina Baby</a></li>
                            <li class="menu-item"><a href="#">No Lácteos</a></li>
                        </ul>
                    </p>
                </div>
                <!-- //Categorias Section End -->
                <!-- Redes Section Start -->
                <div class="col-sm-3 clearfix">
                    <h4>Más Información</h4>
                    <p>
                        <ul id="menu-informacion" class="menu_footer">
                            <li class="menu-item"><a href="https://www.alpina.com" target="_blank">Alpina Digital</a></li>
                            <li class="menu-item"><a href="https://www.alpina.com/corporativo" target="_blank">Corporativo</a></li>
                            <li class="menu-item"><a href="https://www.alpina.com/recetas" target="_blank">Recetas</a></li>

                        </ul>
                    </p>
                    <!--div class="redes_foot">
                        <div class="div_deres"><a href="https://www.facebook.com/alpina" target="_blank"><i class="fa fa-facebook-f color-foot" ></i></a></div>
                        <div class="div_deres"><a href="https://www.instagram.com/alpinacol/" target="_blank"><i class="fa fa-instagram color-foot" ></i></a></div>
                        <div class="div_deres"><a href="https://www.linkedin.com/company/alpina/?trk=vsrp_companies_res_name&trkInfo=VSRPsearchId%3A3286542181450727911739%2CVSRPtargetId%3A48174%2CVSRPcmpt%3Aprimary" target="_blank"><i class="fa fa-linkedin-square color-foot" ></i></a></div>
                        <div class="div_deres"><a href="https://twitter.com/Alpina" target="_blank"><i class="fa fa-twitter color-foot" ></i></a></div>
                        <div class="div_deres"><a href="https://www.youtube.com/user/AlpinaSA" target="_blank"><i class="fa fa-youtube-play color-foot" ></i></a></div>
                        <div style="clear: both;"></div>
                    </div-->
                    </p>
                    
                </div>
                <!-- //redes Section End -->
                <!-- Contacto Section Start -->
                <div class="col-sm-3 clearfix">
                    <h4>Contáctenos</h4>
                        <p>Cualquier inconveniente o duda, comunícate con nuestra línea de atención Alpina en Bogotá
                        
                        </p>
                        <ul id="menu-contacto" class="menu_footer">
                            <li class="menu-item">(+57) 4238600</li>
                            <li class="menu-item">(+57) 5718600</li>
                            <li class="menu-item">(01) 8000529999</li>
                            <li class="menu-item">soporte@alpina.com</li>
                            <li class="menu-item">Formulario de <a href="#">Contacto</a></li>
                        </ul>
                    </div>
                </div>
            <!-- //Contacto Section End -->
            <div>
                <div class="col-md-12 text-center">
                <div class="separador" style="    border-bottom: 2px solid #ffffff;margin-bottom: 10px; width:20%"></div>
                <a href="#" class="menu-item">Términos y Condiciones</a> | <a href="#" class="menu-item">Políticas de Privacidad</a>
                </div>
            </div>
            </div>
        </div>


    </footer>
    <!-- //Footer Section End -->
    <div class="copyright">
        <div class="container">
        <p>Todos los derechos reservados &copy; Alpina, 2018</p>
        </div>
    </div>
    <!--a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top" data-toggle="tooltip" data-placement="left">
        <i class="livicon" data-name="chevron-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
    </a-->
    




        <!-- Modal Direccion -->
    <div class="modal fade" id="ubicacionModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-sucess">
                        <h4 class="modal-title" id="modalLabeldanger">Indícanos desde donde estas visitando nuestra tienda </h4>
                </div>
                
                <div class="modal-body ">
                     <form method="POST" action="{{url('formasenvio/storeciudad')}}" id="addCiuadadForm" name="addCiuadadForm" class="form-horizontal">

                        <input type="hidden" name="base" id="base" value="{{ url('/') }}">

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
                        </form>     
                </div>
                <div class="modal-footer">
                    <button type="button"   class="btn  btn-primary saveubicacion" >Aceptar</button>
                    <button type="button"  class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Direccion -->





    <!--global js starts-->
    <script type="text/javascript" src="{{ asset('assets/js/frontend/lib.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <!--global js end-->
    <!-- javascript para verificar y validar la ubicacion para la venta-->


    <script type="text/javascript">

    /*funciones para crear ubicacion del comprador*/

    
        $(document).ready(function(){

            $("#state_id_ubicacion").select2();
            $("#city_id_ubicacion").select2();
            $(".js-example-responsive").select2({
                width: 'resolve'
            });

             $('.addtocart').addClass('hidden');


            if (localStorage.getItem('ubicacion')!=undefined) {

                ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                if (ubicacion.status=='true'){

                    $('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                                $('.addtocart').removeClass('hidden');


                }else{

                    //$('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);
                    $('#ubicacion_header').html('No Disponible para Despacho');

                    $('.addtocart').addClass('hidden');

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

                                $('#ubicacion_header').html(ubicacion.city_name+', '+ubicacion.state_name);

                                $('.addtocart').removeClass('hidden');


                            }else{

                                //$('#ubicacion_header').html(ubicacion.city_name+' '+ubicacion.state_name);

                                $('#ubicacion_header').html('No Disponible para Despacho');

                                $('.addtocart').addClass('hidden');
                            }

                    }
                });

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


         $(document).ready(function(){

                 var base = $('#base').val();
                   
                $.ajax({
                    url: base+'/configuracion/states/47',
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        localStorage.setItem('states', data.responseText);

                        $('select[name="state_id_ubicacion"]').empty();

                        $.each(data, function(key, value) {
                            $('select[name="state_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });

                    }
                });

         });


         
         $('body').on('click','.addtocart', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            single=$('#single').val();

            url=$(this).attr('href');

            $.get(url, {}, function(data) {

               // $('.cartcontenido').html(data);

               // $('#detailCartModal').modal('show');

               // $('#detalle_carro_front').html($('#modal_cantidad').val()+' '+'Items');

                    $.post(base+'/cart/botones', {id}, function(data) {

                        $('.boton_'+id+'').html(data);


                       if (single==1) {

                            $('.vermas').remove();
                        }

                    });

            });

        });

        $('body').on('click','.updatecart', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            tipo=$(this).data('tipo');

            single=$('#single').val();


            slug=$(this).data('slug');

            cantidad=$('#cantidad_'+id+'').val();

            if (tipo=='suma') {

                cantidad=parseInt(cantidad);

                cantidad++;

            }else{

                cantidad=cantidad-1;
            }
            
                   $.post(base+'/cart/updatecartbotones', {id, slug, cantidad}, function(data) {

                        $('.boton_'+id+'').html('');
                        $('.boton_'+id+'').html(data);

                         if (single==1) {

                            $('.vermas').remove();
                        }



                    });

        });



         $(document).on('change','.cartselect', function(){

            

            base=$('#base').val();

            slug=$(this).data('slug');

            cantidad=$(this).val();

                    $.post(base+'/cart/updatecantidad', { slug, cantidad}, function(data) {

                    });

            });


         $(document).on('click','.delete-item', function(){

            base=$('#base').val();

            slug=$(this).data('slug');

                    $.post(base+'/cart/delproducto', { slug}, function(data) {

                    });

            });




        </script>

    <!--javascript para verificar y validar la ubicacion para la venta -->


    <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->

    
</body>

</html>