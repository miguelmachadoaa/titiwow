<?php


use Illuminate\Support\Facades\Mail;

/* custom routes generated by CRUD */



/*Route::group(['middleware' => ['web', 'auth', 'permission:admin']], function () { 

    Route::group(array('prefix' => 'admin/', 'middleware' => 'admin','as'=>'admin.'), function () {


    Route::resource('productos', 'Admin\AlpProductosController');

    Route::get('productos/{id}/confirm-delete', array('as' => 'productos.confirm-delete', 'uses' => 'Admin\AlpProductosController@getModalDelete'));

    Route::get('productos/{alpProductos}/delete', ['as'=> 'productos.delete', 'uses' => 'Admin\AlpProductosController@destroy']);

    });


});*/







//inicio direcciones productos 

Route::post('signupembajador', 'Admin\AuthController@postSignupEmbajador')->name('admin.signupembajador');
    
Route::get('registroembajadores/{id}', 'Frontend\ClientesFrontController@embajadores')->name('frontend.clientes.registro');


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

        Route::get('{id}/delete', 'Admin\AlpFormasenvioController@destroy')->name('formaspago.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFormaspagoController@getModalDelete')->name('formaspago.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFormaspagoController@getRestore')->name('formaspago.restore');

 	});

    Route::resource('formaspago', 'Admin\AlpFormaspagoController');


    Route::group(['prefix' => 'formasenvio'], function () {

        Route::get('{id}/delete', 'Admin\AlpFormasenvioController@destroy')->name('formasenvio.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFormasenvioController@getModalDelete')->name('formasenvio.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFormasenvioController@getRestore')->name('formasenvio.restore');

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

        Route::get('{id}/confirm-delete', 'Admin\AlpClientesController@getModalDelete')->name('clientes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpClientesController@getRestore')->name('clientes.restore');

        Route::get('{id}/direcciones', 'Admin\AlpClientesController@direcciones')->name('clientes.direcciones');

        });

    Route::resource('clientes', 'Admin\AlpClientesController');

    //fin direcioens clientes

    //Inicio direcciones clientes

    Route::group(['prefix' => 'estatus'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusOrdenesController@destroy')->name('estatus.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusOrdenesController@getModalDelete')->name('estatus.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusOrdenesController@getRestore')->name('estatus.restore');

        });

    Route::resource('estatus', 'Admin\AlpEstatusOrdenesController');


    

    Route::group(['prefix' => 'empresas'], function () {

        Route::get('{id}/delete', 'Admin\AlpEmpresasController@destroy')->name('empresas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEmpresasController@getModalDelete')->name('empresas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEmpresasController@getRestore')->name('empresas.restore');

        });

    Route::resource('empresas', 'Admin\AlpEmpresasController');



       Route::group(['prefix' => 'estatuspagos'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusPagosController@destroy')->name('estatuspagos.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusPagosController@getModalDelete')->name('estatuspagos.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusPagosController@getRestore')->name('estatuspagos.restore');

        });

    Route::resource('estatuspagos', 'Admin\AlpEstatusPagosController');

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

    Route::resource('ordenes', 'Admin\AlpOrdenesController');


    Route::group(['prefix' => 'ordenes'], function () {

        Route::get('{id}/delete', 'Admin\AlpOrdenesController@destroy')->name('ordenes.delete');

        Route::get('{id}/detalle', 'Admin\AlpOrdenesController@detalle')->name('ordenes.detalle');

        Route::get('{id}/confirm-delete', 'Admin\AlpOrdenesController@getModalDelete')->name('ordenes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpOrdenesController@getRestore')->name('ordenes.restore');

        Route::post('/storeconfirm', 'Admin\AlpOrdenesController@storeconfirm')->name('ordenes.storeconfirm');

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

Route::post(    'order/procesar',     [
        //'middleware'=>'auth', 
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

//vaciar un carro
Route::get('cart/vaciar/',['as'=>'cart.vaciar', 'uses'=>'Admin\AlpCartController@vaciar']);

//agregar una direccion desde el detalle del pedido
Route::post('cart/storedir/',['as'=>'cart.storedir', 'uses'=>'Admin\AlpCartController@storedir']);

Route::get('cart/setdir/{direccion}',['as'=>'cart.setdir', 'uses'=>'Admin\AlpCartController@setdir']);

Route::get('cart/deldir/{direccion}',['as'=>'cart.deldir', 'uses'=>'Admin\AlpCartController@deldir']);


//configuracion controller para recuperar esados y ciudades 


Route::get('configuracion/states/{id}',array('as'=>'configuracion.states','uses'=>'Admin\AlpConfiguracionController@selectState'));
       
Route::get('configuracion/cities/{id}',array('as'=>'configuracion.cities','uses'=>'Admin\AlpConfiguracionController@selectCity'));










/* Rutas el Frontend Publico */

Route::get('producto/{slug}', ['as' => 'producto', 'uses' => 'Frontend\ProductosFrontController@show']);

Route::get('productos', 'Frontend\ProductosFrontController@index');


Route::get('categoria/{slug}', ['as' => 'categoria', 'uses' => 'Frontend\ProductosFrontController@categorias']);


/* Fin Rutas Frontend Publico */


/* Ruta privada de amigos */
Route::group(['prefix' => 'clientes', 'namespace'=>'Frontend'], function () {

    Route::get('amigos', 'ClientesFrontController@amigos')->name('amigos');


});

/* Fin ruta amigos */
Route::resource('clientes', 'Frontend\ClientesFrontController');

Route::get('clientes/{id}/compras', 'Frontend\ClientesFrontController@compras')->name('frontend.clientes.compras');

Route::post('storeamigo', 'Frontend\ClientesFrontController@storeamigo')->name('frontend.clientes.storeamigo');

Route::post('delamigo', 'Frontend\ClientesFrontController@delamigo')->name('frontend.clientes.delamigo');


Route::get('miscompras', 'Frontend\ClientesFrontController@miscompras')->name('frontend.clientes.miscompras');

Route::get('misamigos', 'Frontend\ClientesFrontController@misamigos')->name('frontend.clientes.misamigos');


Route::get('clientes/{id}/detalle', 'Frontend\ClientesFrontController@detalle')->name('frontend.clientes.detalle');

Route::get('emailU', function(){
        return new \App\Mail\WelcomeUser('Miguel Machado');
});


Route::post('productos/destacado', ['as'=> 'productos.destacado', 'uses' => 'Admin\AlpProductosController@destacado']);


Route::post('categorias/destacado', ['as'=> 'categorias.destacado', 'uses' => 'Admin\AlpCategoriasController@destacado']);




