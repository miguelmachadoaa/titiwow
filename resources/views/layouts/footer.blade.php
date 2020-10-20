 <!-- Footer Section Start -->
 <footer>
        <div class="container footer-text">
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
                        <ul id="menu-contacto" class="menu_footer">
                            <li class="menu-item">(+571) 4238600</li>
                            <li class="menu-item">(+571) 5718600</li>
                            <li class="menu-item">(01) 8000529999</li>
                            <li class="menu-item">contaccenter@alpina.com</li>
                            <!--li class="menu-item">Formulario de <a href="#">Contacto</a></li-->
                        </ul>
                    
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
    <div class="copyright">
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


    <input type="hidden" name="banpago" id="banpago" value="0">
    

    <button style="width: 0px; display: none; height: 0px;" class="addtocartTrigger">.</button>
    <button style="width: 0px; display: none; height: 0px;" class="updatecartTrigger">.</button>

    <!--global js starts-->
    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/lib.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    <!--global js end-->
    <!-- javascript para verificar y validar la ubicacion para la venta-->


    <script type="text/javascript">

    /*funciones para crear ubicacion del comprador*/

    


     $('.btnpg').click(function(){

        $('.btnpg').fadeOut('fast');
       // $('#banpago').val('1');

        setTimeout(function(){

            $('.btnpg').fadeIn();
             $('#banpago').val('0');

        },10000);

    });

    
        $(document).ready(function(){

            $("#state_id_ubicacion").select2();
            $("#city_id_ubicacion").select2();
            $(".js-example-responsive").select2({
                width: 'resolve'
            });

             //$('.addtocart').addClass('hidden');


            if (localStorage.getItem('ubicacion')!=undefined) {

                ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                if (ubicacion.status=='true'){

                    $('.ubicacion_header a').html(ubicacion.city_name+' '+ubicacion.state_name);

                    $('.addtocart').removeClass('hidden');


                }else{

                    //$('.ubicacion_header a').html(ubicacion.city_name+' '+ubicacion.state_name);
                    $('.ubicacion_header a').html('No Disponible para Despacho');

                    $('.addtocart').addClass('hidden');

                }

            }else{

                data='{"status":"true","city_name":"Bogot\u00e1","state_name":"Cundinamarca","id_ciudad":"62"}';

                localStorage.setItem('ubicacion', data);

                 $('.ubicacion_header a').html('BOGOTÁ CUNDINAMARCA');

              //  $('#ubicacionModal').modal('show', {backdrop: 'static', keyboard: false});
            }

        });



        $('.ubicacion_header a').click(function(e){

            e.preventDefault();

            $('#ubicacionModal').modal('show');

        });


         $('.saveubicacion').click(function (){
    
            var $validator = $('#addCiuadadForm').data('bootstrapValidator').validate();

            if ($validator.isValid()) {

                base=$('#base').val();

                city_id=$('#city_id_ubicacion').val();


                if (city_id==0) {


                    $('.resciudad').html('<div class="alert alert-danger">Debe seleccionar una ciudad.</div>');

                }else{


                    $.ajax({
                    type: "POST",
                    data:{  city_id },
                    url: base+"/configuracion/verificarciudad",
                        
                    complete: function(datos){     

                            ubicacion=JSON.parse(datos.responseText);

                            localStorage.setItem('ubicacion', datos.responseText);

                             $('#ubicacionModal').modal('hide');


                             if (ubicacion.status=='true') {

                                $('.ubicacion_header a').html(ubicacion.city_name+', '+ubicacion.state_name);

                                $('.addtocart').removeClass('hidden');

                                location.reload();


                            }else{

                                //$('.ubicacion_header a').html(ubicacion.city_name+' '+ubicacion.state_name);

                                $('.ubicacion_header a').html('No Disponible para Despacho');

                                $('.addtocart').addClass('hidden');
                            }

                    }
                });




                }

                

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
                            url: base+'/configuracion/citiesModal/'+stateID,
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

                 ubicacion=JSON.parse(localStorage.getItem('ubicacion'));
                   
                $.ajax({
                    url: base+'/configuracion/statesModal/47',
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                       // localStorage.setItem('states', data.responseText());

                       ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                        $('select[name="state_id_ubicacion"]').empty();

                        $.each(data, function(key, value) {

                            if (key==ubicacion.id_state) {

                                   $('select[name="state_id_ubicacion"]').append('<option selected value="'+ key +'">'+ value +'</option>');


                            }else{

                            $('select[name="state_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');

                            }

                        });

                    }
                });

                        $.ajax({
                            url: base+'/configuracion/cities/'+ubicacion.id_state,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                                ubicacion=JSON.parse(localStorage.getItem('ubicacion'));
                                $('select[name="city_id_ubicacion"]').empty();
                                $.each(data, function(key, value) {

                                    if (key==ubicacion.id_ciudad) {

                                    $('select[name="city_id_ubicacion"]').append('<option selected value="'+ key +'">'+ value +'</option>');


                                    }else{

                                    $('select[name="city_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');


                                    }
                                });

                            }
                        });


         });


         
         $(document).on('click','.addtocart', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';

            id=$(this).data('id');

            datasingle=$(this).data('single');

            price=$(this).data('price');

            slug=$(this).data('slug');

            single=$('#single').val();

            url=$(this).attr('href');


            pimagen=$(this).data('pimagen');
            
            name=$(this).data('name');


            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/cart/agregar', {price, slug, datasingle}, function(data) {

                $('.boton_'+id+'').html(data);


                if (data.indexOf("<!--") > -1) {

                        $('.addtocartTrigger').data('imagen', pimagen);
                        $('.addtocartTrigger').data('name', name);
                        $('.addtocartTrigger').data('slug', slug);
                        $('.addtocartTrigger').data('price', price);
                        $('.addtocartTrigger').data('id', id);

                        $('.addtocartTrigger').trigger('click');



                    }




                       if (single==1) {

                            $('.vermas').remove();
                        }

            });

        });


        


         $(document).on('click','.addtocartsingle', function(e){

            e.preventDefault();

            base=$('#base').val();

            imagen=base+'/uploads/files/loader.gif';


            id=$(this).data('id');

            price=$(this).data('price');

            slug=$(this).data('slug');

            single=$('#single').val();

            url=$(this).attr('href');

             pimagen=$(this).data('pimagen');
                name=$(this).data('name');


            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/cart/agregarsingle', {price, slug}, function(data) {

                $('.boton_'+id+'').html(data);

                    if (data.indexOf("<!--") > -1) {

                        $('.addtocartTrigger').data('imagen', pimagen);
                        $('.addtocartTrigger').data('name', name);
                        $('.addtocartTrigger').data('slug', slug);
                        $('.addtocartTrigger').data('price', price);
                        $('.addtocartTrigger').data('id', id);

                        $('.addtocartTrigger').trigger('click');

                    }




                       if (single==1) {

                            $('.vermas').remove();
                        }

               

            });

        });

        $('body').on('click','.updatecart', function(e){

            e.preventDefault();

            base=$('#base').val();


            id=$(this).data('id');

            datasingle=$(this).data('single');

            

            tipo=$(this).data('tipo');

            single=$('#single').val();

            slug=$(this).data('slug');

            cantidad=$('#cantidad_'+id+'').val();

             cantidadinicio=$('#cantidad_'+id+'').val();

            if (tipo=='suma') {

                cantidad=parseInt(cantidad);

                cantidad++;

            }else{

                cantidad=cantidad-1;
            }

            imagen=base+'/uploads/files/loader.gif';

            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');
            
                   $.post(base+'/cart/updatecartbotones', {id, slug, cantidad, datasingle}, function(data) {

                        $('.boton_'+id+'').html('');
                        $('.boton_'+id+'').html(data);


                         if (data.indexOf("<!--") > -1) {

                            $('.updatecartTrigger').data('tipo', tipo);
                            $('.updatecartTrigger').data('cantidad', cantidadinicio);
                            $('.updatecartTrigger').data('single', single);
                            $('.updatecartTrigger').data('id', id);

                            $('.updatecartTrigger').trigger('click');

                        }


                         if (cantidad==0) {


                                $('.updatecartTrigger').data('tipo', tipo);
                                $('.updatecartTrigger').data('cantidad', cantidadinicio);
                                $('.updatecartTrigger').data('single', single);
                                $('.updatecartTrigger').data('id', id);

                                $('.updatecartTrigger').trigger('click');


                        }





                         if (single==1) {

                            $('.vermas').remove();
                        }



                    });

        });


         $('body').on('click','.updatecartsingle', function(e){

            e.preventDefault();

            base=$('#base').val();

            id=$(this).data('id');

            

            tipo=$(this).data('tipo');

            single=$('#single').val();

            slug=$(this).data('slug');

            cantidad=$('#cantidad_'+id+'').val();

            cantidadinicio=$('#cantidad_'+id+'').val();

            if (tipo=='suma') {

                cantidad=parseInt(cantidad);

                cantidad++;

            }else{

                cantidad=cantidad-1;
            }

            imagen=base+'/uploads/files/loader.gif';

            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');
            
                   $.post(base+'/cart/updatecartbotonessingle', {id, slug, cantidad}, function(data) {

                        $('.boton_'+id+'').html('');
                        $('.boton_'+id+'').html(data);


                         if (data.indexOf("<!--") > -1) {

                            $('.updatecartTrigger').data('tipo', tipo);
                            $('.updatecartTrigger').data('cantidad', cantidadinicio);
                            $('.updatecartTrigger').data('single', single);
                            $('.updatecartTrigger').data('id', id);

                            $('.updatecartTrigger').trigger('click');

                        }

                        if (cantidad==0) {


                                $('.updatecartTrigger').data('tipo', tipo);
                                $('.updatecartTrigger').data('cantidad', cantidadinicio);
                                $('.updatecartTrigger').data('single', single);
                                $('.updatecartTrigger').data('id', id);

                                $('.updatecartTrigger').trigger('click');


                        }


                         if (single==1) {

                            $('.vermas').remove();
                        }



                    });

        });



         $(document).on('change','.cartselect', function(){

            base=$('#base').val();

            slug=$(this).data('slug');

            id=$(this).data('id');

            cantidad=$(this).val();

             imagen=base+'/uploads/files/loader.gif';

            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

                    $.post(base+'/cart/updatecantidad', { slug, cantidad}, function(data) {

                    });

                    $.post(base+'/cart/updatecartbotones', {id, slug, cantidad}, function(data) {

                        $('.boton_'+id+'').html('');
                        $('.boton_'+id+'').html(data);

                         if (single==1) {

                            $('.vermas').remove();
                        }



                    });

            });


         $(document).on('click','.delete-item', function(){

            base=$('#base').val();

            slug=$(this).data('slug');

            id=$(this).data('id');

            

            //alert(id);

            single=$('#single').val();

            cantidad=0;


           imagen=base+'/uploads/files/loader.gif';

            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

                    $.post(base+'/cart/delproducto', { slug}, function(data) {

                         $('.boton_'+id+'').html('');
                        $('.boton_'+id+'').html(data);

                         if (single==1) {

                            $('.vermas').remove();
                        }

                    });


            });




        </script>

    <!--javascript para verificar y validar la ubicacion para la venta -->


    <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->

    
</body>

</html>