<?php

/* custom routes generated by CRUD */

//inicio direcciones productos 

Route::group(array('prefix' => 'admin/', 'middleware' => 'admin','as'=>'admin.'), function () {


    Route::resource('productos', 'admin\AlpProductosController');


/*
Route::get('productos', ['as'=> 'productos.index', 'uses' => 'admin\AlpProductosController@index']);

Route::post('productos', ['as'=> 'productos.store', 'uses' => 'admin\AlpProductosController@store']);

Route::get('productos/create', ['as'=> 'productos.create', 'uses' => 'admin\AlpProductosController@create']);

Route::put('productos/{alpProductos}', ['as'=> 'productos.update', 'uses' => 'admin\AlpProductosController@update']);

Route::patch('productos/{alpProductos}', ['as'=> 'productos.update', 'uses' => 'admin\AlpProductosController@update']);

Route::get('productos/{id}/delete', array('as' => 'productos.delete', 'uses' => 'admin\AlpProductosController@getDelete'));



Route::get('productos/{alpProductos}', ['as'=> 'productos.show', 'uses' => 'admin\AlpProductosController@show']);

Route::get('productos/{alpProductos}/edit', ['as'=> 'productos.edit', 'uses' => 'admin\AlpProductosController@edit']);

*/
Route::get('productos/{id}/confirm-delete', array('as' => 'productos.confirm-delete', 'uses' => 'admin\AlpProductosController@getModalDelete'));

Route::get('productos/{alpProductos}/delete', ['as'=> 'productos.delete', 'uses' => 'admin\AlpProductosController@destroy']);

//fin direcciones productos 


//inicio direcciones categorias 

	Route::group(['prefix' => 'categorias'], function () {

        Route::get('{categoria}/delete', 'admin\AlpCategoriasController@destroy')->name('categorias.delete');

        Route::get('{categoria}/confirm-delete', 'admin\AlpCategoriasController@getModalDelete')->name('categorias.confirm-delete');

        Route::get('{categoria}/restore', 'admin\AlpCategoriasController@getRestore')->name('categorias.restore');

        Route::get('{categoria}/detalle', 'admin\AlpCategoriasController@detalle')->name('categorias.detalle');

        Route::post('{categoria}/storeson', 'admin\AlpCategoriasController@storeson')->name('categorias.storeson');

        Route::get('{categoria}/editson', 'admin\AlpCategoriasController@editson')->name('categorias.editson');

        Route::post('{categoria}/updson', 'admin\AlpCategoriasController@updson')->name('categorias.updson');



 	});

    Route::resource('categorias', 'admin\AlpCategoriasController');

    //fin direcciones categorias

    //inicio direcciones forma de pago 

    Route::group(['prefix' => 'formaspago'], function () {

        Route::get('{id}/delete', 'admin\AlpFormasenvioController@destroy')->name('formaspago.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpFormaspagoController@getModalDelete')->name('formaspago.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpFormaspagoController@getRestore')->name('formaspago.restore');

 	});

    Route::resource('formaspago', 'admin\AlpFormaspagoController');

    //fin direcciones forma de pago

    //inicio direcciones froma de envio 

    Route::group(['prefix' => 'formasenvio'], function () {

        Route::get('{id}/delete', 'admin\AlpFormasenvioController@destroy')->name('formasenvio.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpFormasenvioController@getModalDelete')->name('formasenvio.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpFormasenvioController@getRestore')->name('formasenvio.restore');

 	});

    Route::resource('formasenvio', 'admin\AlpFormasenvioController');

    Route::resource('rolpagos', 'admin\AlpRolpagosController');
    Route::resource('rolenvios', 'admin\AlpRolEnviosController');

    //fin direcciones forma de envio 

    //Inicio direcciones marcas

    Route::group(['prefix' => 'marcas'], function () {

        Route::get('{id}/delete', 'admin\AlpMarcasController@destroy')->name('marcas.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpMarcasController@getModalDelete')->name('marcas.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpMarcasController@getRestore')->name('marcas.restore');

 	});

    Route::resource('marcas', 'admin\AlpMarcasController');

    //fin direcioens marcas

    //Inicio direcciones clientes

    Route::group(['prefix' => 'clientes'], function () {

        Route::get('{id}/delete', 'admin\AlpClientesController@destroy')->name('clientes.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpClientesController@getModalDelete')->name('clientes.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpClientesController@getRestore')->name('clientes.restore');

        });

    Route::resource('clientes', 'admin\AlpClientesController');

    //fin direcioens clientes

    //Inicio direcciones clientes

    Route::group(['prefix' => 'estatus'], function () {

        Route::get('{id}/delete', 'admin\AlpEstatusOrdenesController@destroy')->name('estatus.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpEstatusOrdenesController@getModalDelete')->name('estatus.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpEstatusOrdenesController@getRestore')->name('estatus.restore');

        });

    Route::resource('estatus', 'admin\AlpEstatusOrdenesController');

    //fin direcioens clientes

    Route::group(['prefix' => 'transportistas'], function () {

        Route::get('{id}/delete', 'admin\AlpTransportistasController@destroy')->name('transportistas.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpTransportistasController@getModalDelete')->name('transportistas.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpTransportistasController@getRestore')->name('transportistas.restore');

        });

    Route::resource('transportistas', 'admin\AlpTransportistasController');

    //tipos de documentos

    Route::group(['prefix' => 'documentos'], function () {

        Route::get('{id}/delete', 'admin\AlpTipoDocumentosController@destroy')->name('documentos.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpTipoDocumentosController@getModalDelete')->name('documentos.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpTipoDocumentosController@getRestore')->name('documentos.restore');

        });

    Route::resource('documentos', 'admin\AlpImpuestosController');

    //crud impuestos

    Route::group(['prefix' => 'impuestos'], function () {

        Route::get('{id}/delete', 'admin\AlpImpuestosController@destroy')->name('impuestos.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpImpuestosController@getModalDelete')->name('impuestos.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpImpuestosController@getRestore')->name('impuestos.restore');

        });

    Route::resource('impuestos', 'admin\AlpImpuestosController');

    Route::resource('configuracion', 'admin\AlpConfiguracionController');

    Route::resource('ordenes', 'admin\AlpOrdenesController');

    

    Route::group(['prefix' => 'ordenes'], function () {

        Route::get('{id}/delete', 'admin\AlpOrdenesController@destroy')->name('ordenes.delete');

        Route::get('{id}/detalle', 'admin\AlpOrdenesController@detalle')->name('ordenes.detalle');

        Route::get('{id}/confirm-delete', 'admin\AlpOrdenesController@getModalDelete')->name('ordenes.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpOrdenesController@getRestore')->name('ordenes.restore');

        });



    Route::resource('menus', 'admin\AlpMenuController');

    Route::group(['prefix' => 'menus'], function () {

        Route::get('{menu}/delete', 'admin\AlpMenuController@destroy')->name('menus.delete');

        Route::get('{menu}/confirm-delete', 'admin\AlpMenuController@getModalDelete')->name('menus.confirm-delete');

        Route::get('{menu}/restore', 'admin\AlpMenuController@getRestore')->name('menus.restore');

        Route::get('{menu}/detalle', 'admin\AlpMenuController@detalle')->name('menus.detalle');

        Route::post('{menu}/storeson', 'admin\AlpMenuController@storeson')->name('menus.storeson');

        Route::get('{menu}/editson', 'admin\AlpMenuController@editson')->name('menus.editson');

        Route::post('{menu}/updson', 'admin\AlpMenuController@updson')->name('menus.updson');



    });

    




});



//carrito  del video 

Route::get('cart/show', ['as'=>'cart.show', 'uses'=>'admin\AlpCartController@show']);

Route::get('cart/mercadopago', ['as'=>'cart.mercadopago', 'uses'=>'admin\AlpCartController@mercadopago']);

Route::get(    'order/detail',     [
        //'middleware'=>'auth', 
        'as'=>'order.detail', 
        'uses'=>'admin\AlpCartController@orderDetail'
    ]);

Route::post(    'order/procesar',     [
        //'middleware'=>'auth', 
        'as'=>'order.procesar', 
        'uses'=>'admin\AlpCartController@orderProcesar'
    ]);

//inyeccion de dependencias
Route::bind('product', function($slug){
    return App\Models\AlpProductos::where('slug', $slug)->first();
});

//agregar item al carro
Route::get('cart/add/{product}',['as'=>'cart.add', 'uses'=>'admin\AlpCartController@add']);

//responde json
Route::get('cart/addtocart/{product}',['as'=>'cart.addtocart', 'uses'=>'admin\AlpCartController@addtocart']);

//eliminar item del carro
Route::get('cart/delete/{product}',['as'=>'cart.add', 'uses'=>'admin\AlpCartController@delete']);

//actualizar la cantidad de producto en un carro
Route::get('cart/update/{product}/{cantidad}',['as'=>'cart.update', 'uses'=>'admin\AlpCartController@update']);

//vaciar un carro
Route::get('cart/vaciar/',['as'=>'cart.vaciar', 'uses'=>'admin\AlpCartController@vaciar']);

//agregar una direccion desde el detalle del pedido
Route::post('cart/storedir/',['as'=>'cart.storedir', 'uses'=>'admin\AlpCartController@storedir']);

Route::get('cart/setdir/{direccion}',['as'=>'cart.setdir', 'uses'=>'admin\AlpCartController@setdir']);

Route::get('cart/deldir/{direccion}',['as'=>'cart.deldir', 'uses'=>'admin\AlpCartController@deldir']);


//configuracion controller para recuperar esados y ciudades 


Route::get('configuracion/states/{id}',array('as'=>'configuracion.states','uses'=>'admin\AlpConfiguracionController@selectState'));
       
Route::get('configuracion/cities/{id}',array('as'=>'configuracion.cities','uses'=>'admin\AlpConfiguracionController@selectCity'));








/* Rutas el Frontend Publico */

Route::get('producto/{slug}', ['as' => 'producto', 'uses' => 'frontend\ProductosFrontController@show']);

Route::get('productos', 'frontend\ProductosFrontController@index');

/* Fin Rutas Frontend Publico */