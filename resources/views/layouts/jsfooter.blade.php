
    <input type="hidden" name="banpago" id="banpago" value="0">
    

    <button style="width: 0px; display: none; height: 0px;" class="addtocartTrigger">.</button>
    <button style="width: 0px; display: none; height: 0px;" class="updatecartTrigger">.</button>

    <!--global js starts-->
    
    <script type="text/javascript" src="{{ secure_asset('assets/js/frontend/lib.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{ secure_asset('assets/vendors/select2/js/select2.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ secure_asset('assets/js/jquery-ui.min.js') }}"></script>


    <!--global js end-->
    <!-- javascript para verificar y validar la ubicacion para la venta-->

    <!--Chatbot-->
    <!--script type="application/javascript" charset="UTF-8" src="https://cdn.agentbot.net/core/0421146a608cb85197f732accafb785a.js"></script-->
    <!--Fin chatbot-->


   


    
    <script type="text/javascript">

    $(function () {

    //  alert('cargo');

       
    });


    $('.busqueda').on('click', function(e){

        e.preventDefault();

        buscar=$('.typehead').val();

        base=$('#base').val();

       // alert(buscar);

        if ( $(".contain_body").length > 0 ) {
           
            $.ajax({
                type: "GET",
                url: base+"/nuevobuscar/?buscar="+buscar,
                    
                complete: function(datos){     

                    $('.contain_body').html((datos.responseText));
                }

            });


        }else{

            url=base+"/buscar/?buscar="+buscar;

            $(location).attr('href',url);

        }

    });


        $(document).ready(function(){
            $('.cantidadCarrito').html($('.productoscarritodetalle').length);   

            $('.epayco-button-render').hide();

        });

        $('#btnCarrito').on('click', function(){
            $('#CartModal').modal('show');
        });
        

    /*funciones para crear ubicacion del comprador*/

    ban_modal=0;

    jQuery(document).ready(function () {

        $('#BienvenidaModal').modal('show');

            vepopup = localStorage.getItem("vepopup1");

            if (vepopup==null) {

                let modal_popup=$('#modal_popup').val();

                if (modal_popup==1) {

                    ban_modal=1;

                }

                $('#EdadModal').modal('show');

            }

        });

        $('.afirmativo').click(function(){

            ban_modal=0;

            localStorage.setItem("vepopup1", 1);

            location.reload();z

        });

        $(document).ready(function(){

            ubicacionmymodal = localStorage.getItem("ubicacionmymodal");

            if (ubicacionmymodal=='1') {

                $('#miModal').fadeOut();

            }else{

                $('#miModal').removeClass('hidden');

            }

        });


        $('.cerrarMyModal').click(function(){

            localStorage.setItem("ubicacionmymodal", 1);

            $('#miModal').fadeOut();

        });

        $('.ubicacion_header_my').click(function(e){

        e.preventDefault();

        $('#ubicacionModal').modal('show');

        $('#miModal').fadeOut();

        localStorage.setItem("ubicacionmymodal", 1);

        });


     $('.btnpg').click(function(){

        $('.btnpg').fadeOut('fast');

       // console.log($('#banpago').val());
       //  $('#banpago').val('1');

        setTimeout(function(){

            $('.btnpg').fadeIn();
             //$('#banpago').val('0');

        },15000);

    });

    
        $(document).ready(function(){

            base=$('#base').val();

            $.ajax({
                url: base+'/configuracion/getdireccion',
                type: "GET",
                dataType: "json",
                success:function(data) {


                    if(data.id_ciudad=='' || data.id_ciudad==null){

                        $('#ubicacionModal').modal('show', {backdrop: 'static', keyboard: false});

                    }else{

                        if(data.status){

                            ///////////////////////////////////////////////


                            let str_ubicacion='';

                            if(data.city_name!=undefined){
                                str_ubicacion=data.city_name;
                            }

                           /* if(ubicacion.state_name!=undefined || ubicacion.state_name!=null || ubicacion.state_name!=""){
                                str_ubicacion=str_ubicacion+', '+data.state_name;
                            }*/

                            if(data.barrio_name!=undefined  || data.barrio_name!=null || data.barrio_name!=""){
                                if(data.barrio_name.length>1){

                                    
                                str_ubicacion=str_ubicacion+', '+data.barrio_name;

                                }
                            }

                            $('.ubicacion_header a').html('Ubicación: '+str_ubicacion+'');

                            $('.pmimodal').html('Est&aacute; visualizando los productos disponibles en esta ubicaci&oacute;n: <br> <span style="font-size:16px">'+ str_ubicacion +'</span>');

                            $('.addtocart').removeClass('hidden');


                            ////////////////////////////////////////////////////////

                        }else{

                            $('.ubicacion_header a').html('No Disponible para Despacho');

                            $('.addtocart').addClass('hidden');

                        }


                        if (localStorage.getItem('ubicacion')!=undefined) {

                            ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                            if (ubicacion.status=='true'){

                                if(ubicacion.id_ciudad==data.id_ciudad){

                                }else{

                                    ubicacionmymodal = localStorage.getItem("ubicacionmymodal");

                                    if (ubicacionmymodal=='1') {

                                        $('#miModal').fadeOut();

                                    }else{

                                        $('#miModal').removeClass('hidden');

                                    }


                                }


                            }


                        }


                    }


                  //  alert(data.barrio_name);

                }
            });



            $("#barrio_id_ubicacion").fadeOut();

           $("#state_id_ubicacion").select2();

            $("#city_id_ubicacion").select2();

            $(".js-example-responsive").select2({
                width: 'resolve'
            });

             //$('.addtocart').addClass('hidden');


          /*  if (localStorage.getItem('ubicacion')!=undefined) {

                ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                if (ubicacion.status=='true'){

                    let str_ubicacion='';

                    if(ubicacion.city_name!=undefined){
                        str_ubicacion=ubicacion.city_name;
                    }

                    if(ubicacion.state_name!=undefined){
                        str_ubicacion=str_ubicacion+', '+ubicacion.state_name;
                    }

                    if(ubicacion.barrio_name!=undefined){
                        str_ubicacion=str_ubicacion+', '+ubicacion.barrio_name;
                    }

                    $('.ubicacion_header a').html('Ubicación: '+str_ubicacion+'');

                    $('.pmimodal').html('Est&aacute; visualizando los productos disponibles en esta ubicaci&oacute;n: <br> <span style="font-size:16px">'+ str_ubicacion +'</span>');

                    $('.addtocart').removeClass('hidden');


                }else{

                    //$('.ubicacion_header a').html(ubicacion.city_name+' '+ubicacion.state_name);
                    $('.ubicacion_header a').html('No Disponible para Despacho');

                    $('.addtocart').addClass('hidden');

                }

            }else{

                $('#ubicacionModal').modal('show', {backdrop: 'static', keyboard: false});

            }*/

        });



        $('.ubicacion_header a').click(function(e){

            e.preventDefault();

            $('#ubicacionModal').modal('show');

            localStorage.setItem("ubicacionmymodal", 1);

        });


        $(document).on('change','#city_id_ubicacion', function(){

            $(".contenedorBarrio").hide();

            city_id=$('#city_id_ubicacion').val();

            base=$('#base').val();

            $.ajax({
                type: "GET",
                url: base+"/configuracion/"+city_id+"/getalmacen",
                    
                complete: function(datos){     

                    ubicacion=JSON.parse(datos.responseText);

                    let barrios = ubicacion.barrios;

                    let c= Object.keys(barrios).length

                    console.log(c);

                    if(c>0){

                        $('select[name="barrio_id_ubicacion"]').empty();

                        Object.entries(ubicacion.barrios).sort(
                            function (a, b) {
                                return a.barrio_name > b.barrio_name;
                            }
                        ).forEach(([key, value]) => {

                            $('select[name="barrio_id_ubicacion"]').append('<option value="'+ value.id +'">'+ value.barrio_name +'</option>');
                        });

                        $("#barrio_id_ubicacion").select2();

                        $(".contenedorBarrio").show();

                        $(".js-example-responsive").select2({
                            width: 'resolve'
                        });

                    }else{

                        localStorage.setItem('ubicacion', datos.responseText);

                    }

                   // alert(ubicacion.imagen_almacen);

                    if(ubicacion.imagen_almacen==null){

                    }else{

                        $('.imagenubicacion').html('<img src="'+base+'/uploads/almacenes/'+ubicacion.imagen_almacen+'">');

                    }

                }

            });


        });


         $('.saveubicacion').click(function (){
    
            var $validator = $('#addCiuadadForm').data('bootstrapValidator').validate();

            if ($validator.isValid()) {

                base=$('#base').val();

                city_id=$('#city_id_ubicacion').val();

                barrio_id=$('#barrio_id_ubicacion').val();


                if (city_id==0) {

                    $('.resciudad').html('<div class="alert alert-danger">Debe seleccionar una ciudad.</div>');

                }else{


                    $.ajax({
                    type: "POST",
                    data:{  city_id, barrio_id },
                    url: base+"/configuracion/verificarciudad",
                        
                    complete: function(datos){     

                            ubicacion=JSON.parse(datos.responseText);

                            localStorage.setItem('ubicacion', datos.responseText);

                             $('#ubicacionModal').modal('hide');


                             if (ubicacion.status=='true') {

                                $('.ubicacion_header a').html(ubicacion.city_name+' '+ubicacion.state_name+' '+ubicacion.barrio_name);

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



        





        // $('select[name="state_id_ubicacion"]').on('change', function() {

            $(document).ready(function(){
        
            //var stateID = $(this).val();
                var stateID = 47;

                var base = $('#base').val();

                if(stateID) {
                    $.ajax({
                        url: base+'/configuracion/citiesModal/'+stateID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {

                          //  alert(data);

                            $('select[name="city_id_ubicacion"]').empty();

                          
                            $.each(data, function(key, value) {

                                $('select[name="city_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');

                            });


                            $("#city_id_ubicacion").select2();

                            $(".js-example-responsive").select2({
                                width: 'resolve'
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

                 console.log(ubicacion);
                   
                $.ajax({
                    url: base+'/configuracion/statesModal/47',
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                       // localStorage.setItem('states', data.responseText());
                       
                       //console.log(data);

                       ubicacion=JSON.parse(localStorage.getItem('ubicacion'));

                       console.log(ubicacion);

                        $('select[name="state_id_ubicacion"]').empty();

                        $("#state_id_ubicacion").select2();

                        $(".js-example-responsive").select2({
                            width: 'resolve'
                        });


                        $.each(data, function(key, value) {

                            if (ubicacion==null) {

                                $('select[name="state_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');

                            }else{

                                if (key==ubicacion.id_state) {

                                   $('select[name="state_id_ubicacion"]').append('<option selected value="'+ key +'">'+ value +'</option>');

                                }else{

                                        $('select[name="state_id_ubicacion"]').append('<option value="'+ key +'">'+ value +'</option>');

                                }


                            }

                           

                        });


                        
                    }
                });

                if (ubicacion==null) {}else{

                    $.ajax({
                            url: base+'/configuracion/citiesModal/'+ubicacion.id_state,
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


                                $("#city_id_ubicacion").select2();

                                $(".js-example-responsive").select2({
                                    width: 'resolve'
                                });


                                




                            }
                        });
                }


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

            pimagen=$(this).data('imagen');
            
            name=$(this).data('name');
            sku=$(this).data('sku');
            ean=$(this).data('ean');
            categoria=$(this).data('categoria');
            marca=$(this).data('marca');


            dataLayer.push({
            'nombre': name,
            'sku': sku,
            'ean': ean,
            'categoria': categoria,
            'marca': marca,
            'imagen': pimagen,
            'precio': price,
            'slug': slug,
            'event': 'addtocart'
            });

            $('.boton_'+id+'').html('<img style="max-width:32px; max-height:32px;" src="'+imagen+'">');

            $.post(base+'/cart/agregar', {price, slug, datasingle}, function(data) {

                $('.boton_'+id+'').html(data);


                if (data.indexOf("<!--") > -1) {

                    $.get(base+'/cart/getcarrito', function(data) {
                        
                        $('.bodycarrito').html(data);

                        $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                    });
                   

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

                        $.get(base+'/cart/getcarrito', function(data) {
                        
                            $('.bodycarrito').html(data);

                            $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                         });

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

                            $.get(base+'/cart/getcarrito', function(data) {
                        
                        $('.bodycarrito').html(data);

                        $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                    });

                        }


                         if (cantidad==0) {


                            $.get(base+'/cart/getcarrito', function(data) {
                        
                        $('.bodycarrito').html(data);

                        $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                    });


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

                            $.get(base+'/cart/getcarrito', function(data) {
                        
                                    $('.bodycarrito').html(data);

                                    $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                                });

                        }

                        if (cantidad==0) {

                            $.get(base+'/cart/getcarrito', function(data) {
                        
                                $('.bodycarrito').html(data);

                                $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                            });
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

                        $.get(base+'/cart/getcarrito', function(data) {
                        
                        $('.bodycarrito').html(data);

                        $('.cantidadCarrito').html($('.productoscarritodetalle').length);

                    });


                    

                    });


            });




        </script>

    <!--javascript para verificar y validar la ubicacion para la venta -->
