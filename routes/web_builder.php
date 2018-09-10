<?php

/* custom routes generated by CRUD */

//inicio direcciones productos 

Route::group(array('prefix' => 'admin/', 'middleware' => 'admin','as'=>'admin.'), function () {

Route::get('productos', ['as'=> 'productos.index', 'uses' => 'admin\AlpProductosController@index']);

Route::post('productos', ['as'=> 'productos.store', 'uses' => 'admin\AlpProductosController@store']);

Route::get('productos/create', ['as'=> 'productos.create', 'uses' => 'admin\AlpProductosController@create']);
Route::put('productos/{alpProductos}', ['as'=> 'productos.update', 'uses' => 'admin\AlpProductosController@update']);

Route::patch('productos/{alpProductos}', ['as'=> 'productos.update', 'uses' => 'admin\AlpProductosController@update']);

Route::get('productos/{id}/delete', array('as' => 'productos.delete', 'uses' => 'admin\AlpProductosController@getDelete'));

Route::get('productos/{id}/confirm-delete', array('as' => 'productos.confirm-delete', 'uses' => 'admin\AlpProductosController@getModalDelete'));

Route::get('productos/{alpProductos}', ['as'=> 'productos.show', 'uses' => 'admin\AlpProductosController@show']);

Route::get('productos/{alpProductos}/edit', ['as'=> 'productos.edit', 'uses' => 'admin\AlpProductosController@edit']);

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



});



//carrito  del video 

Route::get('cart/show', ['as'=>'cart.show', 'uses'=>'admin\AlpCartController@show']);

Route::get('cart/mercadopago', ['as'=>'cart.mercadopago', 'uses'=>'admin\AlpCartController@mercadopago']);

Route::get(
    'order/detail', 
    [
        //'middleware'=>'auth', 
        'as'=>'order.detail', 
        'uses'=>'admin\AlpCartController@orderDetail'
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








/* Rutas el Frontend Publico */

Route::get('producto/{slug}', ['as' => 'producto', 'uses' => 'frontend\ProductosFrontController@show']);

Route::get('productos', 'frontend\ProductosFrontController@index');

/* Fin Rutas Frontend Publico */