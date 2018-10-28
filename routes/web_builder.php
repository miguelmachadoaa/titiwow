<?php


use Illuminate\Support\Facades\Mail;

/* custom routes generated by CRUD */


//inicio direcciones productos 

Route::post('signupembajador', 'Admin\AuthController@postSignupEmbajador')->name('admin.signupembajador');
    
Route::get('registroembajadores/{id}', 'Frontend\ClientesFrontController@embajadores')->name('frontend.clientes.registro');


Route::post('signupafiliado', 'Admin\AuthController@postSignupAfiliado')->name('admin.signupafiliado');
    
    Route::get('registroafiliado/{id}', 'Admin\AlpEmpresasController@afiliado')->name('frontend.empresas.registro');





Route::group(array('prefix' => 'admin/', 'middleware' => 'admin','as'=>'admin.'), function () {

    Route::resource('productos', 'Admin\AlpProductosController');

    Route::get('productos/{id}/confirmar', array('as' => 'productos.confirmar', 'uses' => 'Admin\AlpProductosController@confirmar'));


    Route::get('productos/{id}/confirm-delete', array('as' => 'productos.confirm-delete', 'uses' => 'Admin\AlpProductosController@getModalDelete'));

    Route::get('productos/{alpProductos}/delete', ['as'=> 'productos.delete', 'uses' => 'Admin\AlpProductosController@destroy']);

    
    

//fin direcciones productos 


//inicio direcciones categorias 

	Route::group(['prefix' => 'categorias'], function () {

        Route::get('{categoria}/delete', 'Admin\AlpCategoriasController@destroy')->name('categorias.delete');

        Route::get('{categoria}/confirm-delete', 'Admin\AlpCategoriasController@getModalDelete')->name('categorias.confirm-delete');

        Route::get('{categoria}/restore', 'Admin\AlpCategoriasController@getRestore')->name('categorias.restore');

        Route::get('{categoria}/detalle', 'Admin\AlpCategoriasController@detalle')->name('categorias.detalle');

        Route::post('{categoria}/storeson', 'Admin\AlpCategoriasController@storeson')->name('categorias.storeson');

        Route::get('{categoria}/editson', 'Admin\AlpCategoriasController@editson')->name('categorias.editson');

        Route::post('{categoria}/updson', 'Admin\AlpCategoriasController@updson')->name('categorias.updson');


 	});

    Route::resource('categorias', 'Admin\AlpCategoriasController');

    //fin direcciones categorias

    //inicio direcciones forma de pago 

    Route::group(['prefix' => 'formaspago'], function () {

        Route::get('{id}/delete', 'Admin\AlpFormaspagoController@destroy')->name('formaspago.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFormaspagoController@getModalDelete')->name('formaspago.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFormaspagoController@getRestore')->name('formaspago.restore');


 	});

    Route::resource('formaspago', 'Admin\AlpFormaspagoController');


    Route::group(['prefix' => 'formasenvio'], function () {

        Route::get('{id}/delete', 'Admin\AlpFormasenvioController@destroy')->name('formasenvio.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFormasenvioController@getModalDelete')->name('formasenvio.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFormasenvioController@getRestore')->name('formasenvio.restore');

        Route::get('{id}/ubicacion', 'Admin\AlpFormasenvioController@ubicacion')->name('formasenvio.ubicacion');
        Route::post('/storecity', 'Admin\AlpFormasenvioController@storecity')->name('formasenvio.storecity');
        Route::post('/delcity', 'Admin\AlpFormasenvioController@delcity')->name('formasenvio.delcity');


 	});


    Route::resource('formasenvio', 'Admin\AlpFormasenvioController');

    Route::resource('rolpagos', 'Admin\AlpRolpagosController');

    Route::resource('rolenvios', 'Admin\AlpRolEnviosController');

    Route::resource('rolconfiguracion', 'Admin\AlpRolConfiguracionController');

    //fin direcciones forma de envio 

    //Inicio direcciones marcas

    Route::group(['prefix' => 'marcas'], function () {

        Route::get('{id}/delete', 'Admin\AlpMarcasController@destroy')->name('marcas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpMarcasController@getModalDelete')->name('marcas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpMarcasController@getRestore')->name('marcas.restore');

 	});

    Route::resource('marcas', 'Admin\AlpMarcasController');

    //fin direcioens marcas

    //Inicio direcciones clientes

    Route::group(['prefix' => 'clientes'], function () {

        Route::get('{id}/delete', 'Admin\AlpClientesController@destroy')->name('clientes.delete');

        Route::get('{id}/detalle', 'Admin\AlpClientesController@detalle')->name('clientes.detalle');

        Route::get('{id}/confirm-delete', 'Admin\AlpClientesController@getModalDelete')->name('clientes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpClientesController@getRestore')->name('clientes.restore');

        Route::get('{id}/direcciones', 'Admin\AlpClientesController@direcciones')->name('clientes.direcciones');

        Route::get('/empresas/list', 'Admin\AlpClientesController@empresas')->name('clientes.empresas');

        Route::get('/inactivos', 'Admin\AlpClientesController@inactivos')->name('clientes.inactivos');

        Route::get('/rechazados', 'Admin\AlpClientesController@rechazados')->name('clientes.rechazados');

        Route::post('/activar', 'Admin\AlpClientesController@activar')->name('clientes.activar');

        Route::post('/rechazar', 'Admin\AlpClientesController@rechazar')->name('clientes.rechazar');

        });

    Route::resource('clientes', 'Admin\AlpClientesController');

    //fin direcioens clientes

    //Inicio estatus Ordenes 

    Route::group(['prefix' => 'estatus'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusOrdenesController@destroy')->name('estatus.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusOrdenesController@getModalDelete')->name('estatus.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusOrdenesController@getRestore')->name('estatus.restore');

        });

    Route::resource('estatus', 'Admin\AlpEstatusOrdenesController');

//fin estatus ordenes

//Inicio Reportes generales

   /* Route::get('reportes/registrados', 'Admin\AlpReportesController@registrados');
    Route::get('reportes/registrados/registradosexcel', 'Admin\AlpReportesController@excelUserNew')->name('reportes.registrados.registradosexcel');*/

   Route::get('reportes/registrados', 'Admin\AlpReportesController@indexreg');
   Route::get('reportes/registrados/export', 'Admin\AlpReportesController@export');

//Fin Reportes generales
    

    Route::group(['prefix' => 'empresas'], function () {

        Route::get('{id}/delete', 'Admin\AlpEmpresasController@destroy')->name('empresas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEmpresasController@getModalDelete')->name('empresas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEmpresasController@getRestore')->name('empresas.restore');


        Route::get('{id}/afiliados', 'Admin\AlpEmpresasController@afiliados')->name('empresas.afiliados');

        });

    Route::resource('empresas', 'Admin\AlpEmpresasController');

    Route::get('empresas/{id}/invitaciones', 'Admin\AlpEmpresasController@invitaciones')->name('empresas.invitaciones');

    Route::post('empresas/storeamigo', 'Admin\AlpEmpresasController@storeamigo')->name('empresas.storeamigo');

    Route::post('empresas/delamigo', 'Admin\AlpEmpresasController@delamigo')->name('empresas.delamigo');

    /*proceso para registro de afiliado a una empresa */

    



       Route::group(['prefix' => 'estatuspagos'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusPagosController@destroy')->name('estatuspagos.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusPagosController@getModalDelete')->name('estatuspagos.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusPagosController@getRestore')->name('estatuspagos.restore');

        });

    Route::resource('estatuspagos', 'Admin\AlpEstatusPagosController');


       Route::group(['prefix' => 'estatusenvios'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusEnviosController@destroy')->name('estatusenvios.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusEnviosController@getModalDelete')->name('estatusenvios.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusEnviosController@getRestore')->name('estatusenvios.restore');

        });

    Route::resource('estatusenvios', 'Admin\AlpEstatusEnviosController');

    //fin direcioens clientes

    Route::group(['prefix' => 'transportistas'], function () {

        Route::get('{id}/delete', 'Admin\AlpTransportistasController@destroy')->name('transportistas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpTransportistasController@getModalDelete')->name('transportistas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpTransportistasController@getRestore')->name('transportistas.restore');

        });

    Route::resource('transportistas', 'Admin\AlpTransportistasController');

    //tipos de documentos

    Route::group(['prefix' => 'documentos'], function () {

        Route::get('{id}/delete', 'Admin\AlpTipoDocumentosController@destroy')->name('documentos.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpTipoDocumentosController@getModalDelete')->name('documentos.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpTipoDocumentosController@getRestore')->name('documentos.restore');

        });

    Route::resource('documentos', 'Admin\AlpTipoDocumentosController');

    //crud impuestos

    Route::group(['prefix' => 'impuestos'], function () {

        Route::get('{id}/delete', 'Admin\AlpImpuestosController@destroy')->name('impuestos.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpImpuestosController@getModalDelete')->name('impuestos.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpImpuestosController@getRestore')->name('impuestos.restore');

        });



    Route::resource('impuestos', 'Admin\AlpImpuestosController');


    //crud impuestos

    Route::group(['prefix' => 'sedes'], function () {

        Route::get('{id}/delete', 'Admin\AlpSedesController@destroy')->name('sedes.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpSedesController@getModalDelete')->name('sedes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpSedesController@getRestore')->name('sedes.restore');

        });

    Route::resource('sedes', 'Admin\AlpSedesController');



    Route::resource('configuracion', 'Admin\AlpConfiguracionController');

    Route::post('configuracion/storecity', 'Admin\AlpConfiguracionController@storecity')->name('configuracion.storecity');

    Route::post('configuracion/delcity', 'Admin\AlpConfiguracionController@delcity')->name('configuracion.delcity');


    Route::resource('ordenes', 'Admin\AlpOrdenesController');


    Route::group(['prefix' => 'ordenes'], function () {

        Route::get('{id}/delete', 'Admin\AlpOrdenesController@destroy')->name('ordenes.delete');

        Route::get('{id}/detalle', 'Admin\AlpOrdenesController@detalle')->name('ordenes.detalle');

        Route::get('{id}/confirm-delete', 'Admin\AlpOrdenesController@getModalDelete')->name('ordenes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpOrdenesController@getRestore')->name('ordenes.restore');

        Route::post('/storeconfirm', 'Admin\AlpOrdenesController@storeconfirm')->name('ordenes.storeconfirm');

        Route::post('/aprobar', 'Admin\AlpOrdenesController@aprobar')->name('ordenes.aprobar');

        Route::get('/empresas/list', 'Admin\AlpOrdenesController@empresas')->name('ordenes.empresas');

        Route::get('/aprobados/list', 'Admin\AlpOrdenesController@aprobados')->name('ordenes.aprobados');

    });





     Route::resource('envios', 'Admin\AlpEnviosController');


    Route::group(['prefix' => 'envios'], function () {

        Route::get('{id}/delete', 'Admin\AlpEnviosController@destroy')->name('envios.delete');

        Route::get('{id}/detalle', 'Admin\AlpEnviosController@detalle')->name('envios.detalle');

        Route::get('{id}/confirm-delete', 'Admin\AlpEnviosController@getModalDelete')->name('envios.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEnviosController@getRestore')->name('envios.restore');

        Route::post('/updatestatus', 'Admin\AlpEnviosController@updatestatus')->name('envios.updatestatus');

        Route::get('/empresas/list', 'Admin\AlpEnviosController@empresas')->name('envios.empresas');

    });



    Route::resource('menus', 'Admin\AlpMenuController');

    Route::group(['prefix' => 'menus'], function () {

        Route::get('{menu}/delete', 'Admin\AlpMenuController@destroy')->name('menus.delete');

        Route::get('{menu}/confirm-delete', 'Admin\AlpMenuController@getModalDelete')->name('menus.confirm-delete');

        Route::get('{menu}/restore', 'Admin\AlpMenuController@getRestore')->name('menus.restore');

        Route::get('{menu}/detalle', 'Admin\AlpMenuController@detalle')->name('menus.detalle');

        Route::get('{menu}/submenu', 'Admin\AlpMenuController@submenu')->name('menus.submenu');

        Route::post('{menu}/storeson', 'Admin\AlpMenuController@storeson')->name('menus.storeson');

        Route::post('{menu}/storesub', 'Admin\AlpMenuController@storesub')->name('menus.storesub');

        Route::get('{menu}/editson', 'Admin\AlpMenuController@editson')->name('menus.editson');

        Route::post('{menu}/updson', 'Admin\AlpMenuController@updson')->name('menus.updson');

    });


});




Route::get('mercadopago', ['as'=>'frontend.mercadopago', 'uses'=>'Admin\AlpMercadoPagoController@mercadopago']);



//carrito  del video 

Route::get('cart/show', ['as'=>'cart.show', 'uses'=>'Admin\AlpCartController@show']);

Route::get('cart/mercadopago', ['as'=>'cart.mercadopago', 'uses'=>'Admin\AlpCartController@mercadopago']);

Route::get(    'order/detail',     [
        //'middleware'=>'auth', 
        'as'=>'order.detail', 
        'uses'=>'Admin\AlpCartController@orderDetail'
    ]);

Route::get(   'order/failure/',     [
        //'middleware'=>'auth', 
        'as'=>'order.failure', 
        'uses'=>'Admin\AlpCartController@failure'
    ]);

Route::get(   'order/success/',     [
        //'middleware'=>'auth', 
        'as'=>'order.success', 
        'uses'=>'Admin\AlpCartController@success'
    ]);


Route::post(    'order/procesar',     [ 
        'as'=>'order.procesar', 
        'uses'=>'Admin\AlpCartController@orderProcesar'
    ]);

//inyeccion de dependencias
Route::bind('product', function($slug){
    return App\Models\AlpProductos::where('slug', $slug)->first();
});

//agregar item al carro
Route::get('cart/add/{product}',['as'=>'cart.add', 'uses'=>'Admin\AlpCartController@add']);

//responde json
Route::get('cart/addtocart/{product}',['as'=>'cart.addtocart', 'uses'=>'Admin\AlpCartController@addtocart']);

//eliminar item del carro
Route::get('cart/delete/{product}',['as'=>'cart.add', 'uses'=>'Admin\AlpCartController@delete']);

//actualizar la cantidad de producto en un carro
Route::get('cart/update/{product}/{cantidad}',['as'=>'cart.update', 'uses'=>'Admin\AlpCartController@update']);

Route::post('cart/updatecart/',['as'=>'cart.updatecart', 'uses'=>'Admin\AlpCartController@updatecart']);


//vaciar un carro
Route::get('cart/vaciar/',['as'=>'cart.vaciar', 'uses'=>'Admin\AlpCartController@vaciar']);

//agregar una direccion desde el detalle del pedido
Route::post('cart/storedir/',['as'=>'cart.storedir', 'uses'=>'Admin\AlpCartController@storedir']);

Route::post('cart/verificarDireccion/',['as'=>'cart.verificarDireccion', 'uses'=>'Admin\AlpCartController@verificarDireccion']);


Route::get('cart/setdir/{direccion}',['as'=>'cart.setdir', 'uses'=>'Admin\AlpCartController@setdir']);

Route::get('cart/deldir/{direccion}',['as'=>'cart.deldir', 'uses'=>'Admin\AlpCartController@deldir']);


//configuracion controller para recuperar esados y ciudades 


Route::get('configuracion/states/{id}',array('as'=>'configuracion.states','uses'=>'Admin\AlpConfiguracionController@selectState'));
       
Route::get('configuracion/cities/{id}',array('as'=>'configuracion.cities','uses'=>'Admin\AlpConfiguracionController@selectCity'));

Route::post('configuracion/verificarciudad', 'Admin\AlpConfiguracionController@verificarciudad')->name('configuracion.verificarciudad');










/* Rutas el Frontend Publico */

Route::get('producto/{slug}', ['as' => 'producto', 'uses' => 'Frontend\ProductosFrontController@show']);

Route::get('productos', 'Frontend\ProductosFrontController@index');


Route::get('categoria/{slug}', ['as' => 'categoria', 'uses' => 'Frontend\ProductosFrontController@categorias']);

Route::get("buscar","Frontend\ProductosFrontController@mySearch");

/* Fin Rutas Frontend Publico */


/* Ruta privada de amigos */
Route::group(['prefix' => 'clientes', 'namespace'=>'Frontend'], function () {

    Route::get('amigos', 'ClientesFrontController@amigos')->name('amigos');


});

/* Fin ruta amigos */
Route::get('clientes', 'Frontend\ClientesFrontController@index')->name('clientes');

Route::get('clientes/{id}/compras', 'Frontend\ClientesFrontController@compras')->name('frontend.clientes.compras');

Route::post('storeamigo', 'Frontend\ClientesFrontController@storeamigo')->name('frontend.clientes.storeamigo');



Route::post('delamigo', 'Frontend\ClientesFrontController@delamigo')->name('frontend.clientes.delamigo');

Route::post('clientes/deleteamigo', 'Frontend\ClientesFrontController@deleteamigo')->name('frontend.clientes.deleteamigo');

Route::post('clientes/storedir/',['as'=>'clientes.storedir', 'uses'=>'Frontend\ClientesFrontController@storedir']);
Route::post('clientes/deldir/',['as'=>'clientes.deldir', 'uses'=>'Frontend\ClientesFrontController@deldir']);



Route::get('miscompras', 'Frontend\ClientesFrontController@miscompras')->name('frontend.clientes.miscompras');

Route::get('misamigos', 'Frontend\ClientesFrontController@misamigos')->name('frontend.clientes.misamigos');

Route::get('misdirecciones', 'Frontend\ClientesFrontController@misdirecciones')->name('frontend.clientes.misdirecciones');


Route::get('clientes/{id}/detalle', 'Frontend\ClientesFrontController@detalle')->name('frontend.clientes.detalle');




Route::post('productos/destacado', ['as'=> 'productos.destacado', 'uses' => 'Admin\AlpProductosController@destacado']);
Route::post('productos/desactivar', ['as'=> 'productos.desactivar', 'uses' => 'Admin\AlpProductosController@desactivar']);


Route::post('categorias/destacado', ['as'=> 'categorias.destacado', 'uses' => 'Admin\AlpCategoriasController@destacado']);


Route::get('emailU', function(){
        return new \App\Mail\WelcomeUser('Miguel Machado');
});

Route::get('emailAfiliado', function(){
        return new \App\Mail\NotificacionAfiliado('Miguel', 'Machado', 'sdklfjasfasdfasdklfasf', 'Empresa');
});

Route::get('emailAmigo', function(){
        return new \App\Mail\NotificacionAmigo('Miguel', 'Machado', 'sdklfjasfasdfasdklfasf', 'Embajador Embajador');
});


