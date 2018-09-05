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



/* Rutas el Frontend Publico */

Route::get('producto/{slug}', ['as' => 'producto', 'uses' => 'frontend\ProductosFrontController@show']);

Route::get('productos', 'frontend\ProductosFrontController@index');

/* Fin Rutas Frontend Publico */