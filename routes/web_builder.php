<?php

/* custom routes generated by CRUD */


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



    Route::group(['prefix' => 'formaspago'], function () {

        Route::get('{id}/delete', 'admin\AlpFormasenvioController@destroy')->name('formaspago.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpFormaspagoController@getModalDelete')->name('formaspago.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpFormaspagoController@getRestore')->name('formaspago.restore');

 	});

    Route::resource('formaspago', 'admin\AlpFormaspagoController');


    Route::group(['prefix' => 'formasenvio'], function () {

        Route::get('{id}/delete', 'admin\AlpFormasenvioController@destroy')->name('formasenvio.delete');

        Route::get('{id}/confirm-delete', 'admin\AlpFormasenvioController@getModalDelete')->name('formasenvio.confirm-delete');

        Route::get('{id}/restore', 'admin\AlpFormasenvioController@getRestore')->name('formasenvio.restore');

 	});

    Route::resource('formasenvio', 'admin\AlpFormasenvioController');



});

