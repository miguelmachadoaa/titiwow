<?php


use Illuminate\Support\Facades\Mail;

/* custom routes generated by CRUD */


//inicio direcciones productos 

Route::post('signupembajador', 'Admin\AuthController@postSignupEmbajador')->name('admin.signupembajador');
    
Route::get('registroembajadores/{id}', 'Frontend\ClientesFrontController@embajadores')->name('frontend.clientes.registro');


Route::post('signupafiliado', 'Admin\AuthController@postSignupAfiliado')->name('admin.signupafiliado');
    
Route::get('registroafiliado/{id}', 'Admin\AlpEmpresasController@afiliado')->name('frontend.empresas.registro');

Route::post('admin/ordenes/storeconfirm', 'Admin\AlpOrdenesController@storeconfirm')->name('ordenes.storeconfirm');



 Route::get('reportes/exportcronlogisticaexport', 'Admin\AlpReportesController@exportcronlogisticaexport')->name('reportes.exportcronlogisticaexport');

Route::get('reportes/cronnuevosusuariosexport', 'Admin\AlpReportesController@cronnuevosusuariosexport')->name('reportes.cronnuevosusuariosexport');

Route::get('reportes/cronexportproductosb', 'Admin\AlpReportesController@cronexportproductosb')->name('reportes.cronexportproductosb');





Route::group(array('prefix' => 'admin/', 'middleware' => 'admin','as'=>'admin.'), function () {


    Route::get('groups/{role}/permisos', ['uses' => 'Admin\GroupsController@permissions', 'as' => 'groups.permisos']);
    
    Route::post('groups/{role}/save', ['uses' => 'Admin\GroupsController@save', 'as' => 'groups.save']);

    Route::post('groups/{role}/guardar', ['uses' => 'Admin\GroupsController@guardar', 'as' => 'groups.guardar']);




    Route::resource('sliders', 'Admin\AlpSlidersController');

    Route::get('sliders/{id}/confirm-delete', array('as' => 'sliders.confirm-delete', 'uses' => 'Admin\AlpSlidersController@getModalDelete'));

    Route::get('sliders/{alpSliders}/delete', ['as'=> 'sliders.delete', 'uses' => 'Admin\AlpSlidersController@destroy']);









    Route::resource('productos', 'Admin\AlpProductosController');

    Route::get('productos/{id}/confirmar', array('as' => 'productos.confirmar', 'uses' => 'Admin\AlpProductosController@confirmar'));

    Route::get('productos/{id}/relacionado', array('as' => 'productos.relacionado', 'uses' => 'Admin\AlpProductosController@relacionado'));

    Route::post('productos/addrelacionado', array('as' => 'productos.addrelacionado', 'uses' => 'Admin\AlpProductosController@addrelacionado'));

    Route::post('productos/delrelacionado', array('as' => 'productos.delrelacionado', 'uses' => 'Admin\AlpProductosController@delrelacionado'));


    Route::get('productos/{id}/confirm-delete', array('as' => 'productos.confirm-delete', 'uses' => 'Admin\AlpProductosController@getModalDelete'));

    Route::get('productos/{alpProductos}/delete', ['as'=> 'productos.delete', 'uses' => 'Admin\AlpProductosController@destroy']);

    Route::get('productos/verificar/referenciasap', ['as'=> 'productos.referenciasap', 'uses' => 'Admin\AlpProductosController@referenciasap']);

    Route::get('productos/verificar/referencia', ['as'=> 'productos.referencia', 'uses' => 'Admin\AlpProductosController@referencia']);



   


    
    

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
    Route::post('categorias/create', 'Admin\AlpCategoriasController@store');

    Route::resource('categorias', 'Admin\AlpCategoriasController');

    //fin direcciones categorias

    //crear cupones 



    Route::group(['prefix' => 'cupones'], function () {

        Route::get('{id}/delete', 'Admin\AlpCuponesController@destroy')->name('cupones.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpCuponesController@getModalDelete')->name('cupones.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpCuponesController@getRestore')->name('cupones.restore');

        Route::get('{id}/configurar', 'Admin\AlpCuponesController@configurar')->name('cupones.configurar');

        Route::post('{id}/addcategoria', 'Admin\AlpCuponesController@addcategoria')->name('cupones.addcategoria');

        Route::post('{id}/delcategoria', 'Admin\AlpCuponesController@delcategoria')->name('cupones.delcategoria');

         Route::post('{id}/addempresa', 'Admin\AlpCuponesController@addempresa')->name('cupones.addempresa');

        Route::post('{id}/delempresa', 'Admin\AlpCuponesController@delempresa')->name('cupones.delempresa');


        Route::post('{id}/addproducto', 'Admin\AlpCuponesController@addproducto')->name('cupones.addproducto');

        Route::post('{id}/delproducto', 'Admin\AlpCuponesController@delproducto')->name('cupones.delproducto');


        Route::post('{id}/addcliente', 'Admin\AlpCuponesController@addcliente')->name('cupones.addcliente');

        Route::post('{id}/delcliente', 'Admin\AlpCuponesController@delcliente')->name('cupones.delcliente');



    });
    Route::post('cupones/create', 'Admin\AlpCuponesController@store');

    Route::resource('cupones', 'Admin\AlpCuponesController');




    //fin cupones 


    Route::group(['prefix' => 'formaspago'], function () {

        Route::get('{id}/delete', 'Admin\AlpFormaspagoController@destroy')->name('formaspago.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFormaspagoController@getModalDelete')->name('formaspago.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFormaspagoController@getRestore')->name('formaspago.restore');


 	});
     Route::post('formaspago/create', 'Admin\AlpFormaspagoController@store');
     Route::resource('formaspago', 'Admin\AlpFormaspagoController');



    Route::group(['prefix' => 'inventario'], function () {

        Route::get('{id}/delete', 'Admin\AlpInventarioController@destroy')->name('inventario.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpInventarioController@getModalDelete')->name('inventario.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpInventarioController@getRestore')->name('inventario.restore');


    });
    Route::post('inventario/create', 'Admin\AlpInventarioController@store');
    Route::resource('inventario', 'Admin\AlpInventarioController');


    Route::group(['prefix' => 'formasenvio'], function () {

        Route::get('{id}/delete', 'Admin\AlpFormasenvioController@destroy')->name('formasenvio.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFormasenvioController@getModalDelete')->name('formasenvio.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFormasenvioController@getRestore')->name('formasenvio.restore');

        Route::get('{id}/ubicacion', 'Admin\AlpFormasenvioController@ubicacion')->name('formasenvio.ubicacion');
        Route::post('/storecity', 'Admin\AlpFormasenvioController@storecity')->name('formasenvio.storecity');
        Route::post('/delcity', 'Admin\AlpFormasenvioController@delcity')->name('formasenvio.delcity');


 	});

    Route::post('formasenvio/create', 'Admin\AlpFormasenvioController@store');
    Route::resource('formasenvio', 'Admin\AlpFormasenvioController');

    Route::post('rolpagos/create', 'Admin\AlpRolPagosController@store');
    Route::resource('rolpagos', 'Admin\AlpRolPagosController');

    Route::post('rolenvios/create', 'Admin\AlpRolEnviosController@store');
    Route::resource('rolenvios', 'Admin\AlpRolEnviosController');

    Route::resource('rolconfiguracion', 'Admin\AlpRolConfiguracionController');

    //fin direcciones forma de envio 

    //Inicio direcciones marcas

    Route::group(['prefix' => 'marcas'], function () {

        Route::get('{id}/delete', 'Admin\AlpMarcasController@destroy')->name('marcas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpMarcasController@getModalDelete')->name('marcas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpMarcasController@getRestore')->name('marcas.restore');

 	});
    Route::post('marcas/create', 'Admin\AlpMarcasController@store');
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

        Route::get('/delcliente', 'Admin\AlpClientesController@eliminar')->name('clientes.eliminar');

        });
    Route::post('clientes/store', 'Admin\AlpClientesController@store');
    
    Route::resource('clientes', 'Admin\AlpClientesController');

    //fin direcioens clientes

    //Inicio estatus Ordenes 

    Route::group(['prefix' => 'estatus'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusOrdenesController@destroy')->name('estatus.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusOrdenesController@getModalDelete')->name('estatus.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusOrdenesController@getRestore')->name('estatus.restore');

        });
    Route::post('estatus/store', 'Admin\AlpEstatusOrdenesController@store');
    Route::resource('estatus', 'Admin\AlpEstatusOrdenesController');

    //fin estatus ordenes

//Inicio Reportes generales


    /******************Rutas para descarga de archivs*************************/

    Route::get('reportes/exportcronlogisticaexport', 'Admin\AlpReportesController@exportcronlogisticaexport')->name('reportes.exportcronlogisticaexport');

    Route::get('reportes/cronnuevosusuariosexport', 'Admin\AlpReportesController@cronnuevosusuariosexport')->name('reportes.cronnuevosusuariosexport');


    /*******************************************/












   Route::get('reportes/registrados', 'Admin\AlpReportesController@indexreg')->name('reportes.registrados');
   
   Route::post('reportes/registrados/export', 'Admin\AlpReportesController@export')->name('reportes.export');

   Route::get('reportes/ventastotales', 'Admin\AlpReportesController@ventastotales')->name('reportes.ventastotales');
   
   Route::post('reportes/exportventastotales', 'Admin\AlpReportesController@exportventastotales')->name('reportes.exportventastotales');

   





   Route::get('reportes/ventas', 'Admin\AlpReportesController@ventas')->name('reportes.ventas');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportventas', 'Admin\AlpReportesController@exportventas')->name('reportes.exportventas');


    Route::get('reportes/productos', 'Admin\AlpReportesController@productos')->name('reportes.productos');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportproductos', 'Admin\AlpReportesController@exportproductos')->name('reportes.exportproductos');


   Route::get('reportes/productosb', 'Admin\AlpReportesController@productosb')->name('reportes.productosb');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
  
   Route::post('reportes/exportproductosb', 'Admin\AlpReportesController@exportproductosb')->name('reportes.exportproductosb');


    Route::get('reportes/carrito', 'Admin\AlpReportesController@carrito')->name('reportes.carrito');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportcarrito', 'Admin\AlpReportesController@exportcarrito')->name('reportes.exportcarrito');


   Route::get('reportes/financiero', 'Admin\AlpReportesController@financiero')->name('reportes.financiero');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportfinanciero', 'Admin\AlpReportesController@exportfinanciero')->name('reportes.exportfinanciero');

    

    Route::get('reportes/masterfile', 'Admin\AlpReportesController@masterfile')->name('reportes.masterfile');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportmasterfile', 'Admin\AlpReportesController@exportmasterfile')->name('reportes.exportmasterfile');


   Route::get('reportes/masterfileembajadores', 'Admin\AlpReportesController@masterfileembajadores')->name('reportes.masterfileembajadores');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportmasterfileembajadores', 'Admin\AlpReportesController@exportmasterfileembajadores')->name('reportes.exportmasterfileembajadores');


   Route::get('reportes/masterfileamigos', 'Admin\AlpReportesController@masterfileamigos')->name('reportes.masterfileamigos');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportmasterfileamigos', 'Admin\AlpReportesController@exportmasterfileamigos')->name('reportes.exportmasterfileamigos');



    Route::get('reportes/logistica', 'Admin\AlpReportesController@logistica')->name('reportes.logistica');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportlogistica', 'Admin\AlpReportesController@exportlogistica')->name('reportes.exportlogistica');


    Route::get('reportes/consolidado', 'Admin\AlpReportesController@consolidado')->name('reportes.consolidado');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportconsolidado', 'Admin\AlpReportesController@exportconsolidado')->name('reportes.exportconsolidado');


   Route::get('reportes/productostotales', 'Admin\AlpReportesController@productostotales')->name('reportes.productostotales');
   //Route::post('reportes/ventasexport', 'Admin\AlpReportesController@ventasexport');
   Route::post('reportes/exportproductostotales', 'Admin\AlpReportesController@exportproductostotales')->name('reportes.exportproductostotales');

//Fin Reportes generales


/* Vista Alpinistas (Importar)*/


Route::group(['prefix' => 'alpinistas'], function () {

    Route::post('import', 'Admin\AlpAlpinistasController@import');
    Route::post('retirar', 'Admin\AlpAlpinistasController@retiro');

});
Route::resource('alpinistas', 'Admin\AlpAlpinistasController');




/*Fin Vista Alpinistas */
    
/* Facturas Masivas */
Route::group(['prefix' => 'facturasmasivas'], function () {

    Route::post('import', 'Admin\AlpFacturasController@import');

});

/*Inicio Invitaciones Import*/
Route::group(['prefix' => 'invitacionesmasivas'], function () {

    Route::post('import', 'Admin\AlpEmpresasController@import');

});
/*Fin import invitaciones */
Route::resource('facturasmasivas', 'Admin\AlpFacturasController');

/* Facturas Masivas Fin*/
    Route::group(['prefix' => 'empresas'], function () {

        Route::get('{id}/delete', 'Admin\AlpEmpresasController@destroy')->name('empresas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEmpresasController@getModalDelete')->name('empresas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEmpresasController@getRestore')->name('empresas.restore');


        Route::get('{id}/afiliados', 'Admin\AlpEmpresasController@afiliados')->name('empresas.afiliados');


        Route::get('invitacionesmasiv', 'Admin\AlpEmpresasController@invitacionesmasiv')->name('empresas.invitacionesmasiv');

        });
    Route::post('empresas/create', 'Admin\AlpEmpresasController@store');
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
    Route::post('estatuspagos/create', 'Admin\AlpEstatusPagosController@store');
    Route::resource('estatuspagos', 'Admin\AlpEstatusPagosController');


    Route::group(['prefix' => 'estatusenvios'], function () {

        Route::get('{id}/delete', 'Admin\AlpEstatusEnviosController@destroy')->name('estatusenvios.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpEstatusEnviosController@getModalDelete')->name('estatusenvios.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpEstatusEnviosController@getRestore')->name('estatusenvios.restore');

        });
    Route::post('estatusenvios/create', 'Admin\AlpEstatusEnviosController@store');
    Route::resource('estatusenvios', 'Admin\AlpEstatusEnviosController');

    //fin direcioens clientes

    Route::group(['prefix' => 'transportistas'], function () {

        Route::get('{id}/delete', 'Admin\AlpTransportistasController@destroy')->name('transportistas.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpTransportistasController@getModalDelete')->name('transportistas.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpTransportistasController@getRestore')->name('transportistas.restore');

        });

    Route::post('transportistas/create', 'Admin\AlpTransportistasController@store');

    Route::resource('transportistas', 'Admin\AlpTransportistasController');



    Route::group(['prefix' => 'feriados'], function () {

        Route::get('{id}/delete', 'Admin\AlpFeriadosController@destroy')->name('feriados.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpFeriadosController@getModalDelete')->name('feriados.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpFeriadosController@getRestore')->name('feriados.restore');

        });
    
    Route::post('feriados/create', 'Admin\AlpFeriadosController@store');

    Route::resource('feriados', 'Admin\AlpFeriadosController');

    //tipos de documentos

    Route::group(['prefix' => 'documentos'], function () {

        Route::get('{id}/delete', 'Admin\AlpTipoDocumentosController@destroy')->name('documentos.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpTipoDocumentosController@getModalDelete')->name('documentos.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpTipoDocumentosController@getRestore')->name('documentos.restore');

        });
    Route::post('documentos/create', 'Admin\AlpTipoDocumentosController@store');
    Route::resource('documentos', 'Admin\AlpTipoDocumentosController');

    //crud impuestos

    Route::group(['prefix' => 'impuestos'], function () {

        Route::get('{id}/delete', 'Admin\AlpImpuestosController@destroy')->name('impuestos.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpImpuestosController@getModalDelete')->name('impuestos.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpImpuestosController@getRestore')->name('impuestos.restore');

        });


    Route::post('impuestos/create', 'Admin\AlpImpuestosController@store');
    Route::resource('impuestos', 'Admin\AlpImpuestosController');


    //crud impuestos

    Route::group(['prefix' => 'sedes'], function () {

        Route::get('{id}/delete', 'Admin\AlpSedesController@destroy')->name('sedes.delete');

        Route::get('{id}/confirm-delete', 'Admin\AlpSedesController@getModalDelete')->name('sedes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpSedesController@getRestore')->name('sedes.restore');

        });
    Route::post('sedes/create', 'Admin\AlpSedesController@store');
    Route::resource('sedes', 'Admin\AlpSedesController');

    /*Inicio CMS*/

    Route::group(['prefix' => 'cms'], function () {
        Route::get('{cms}/delete', 'Admin\AlpCmsController@destroy')->name('cms.delete');
        Route::get('{cms}/confirm-delete', 'Admin\AlpCmsController@getModalDelete')->name('cms.confirm-delete');
        Route::get('{cms}/restore', 'Admin\AlpCmsController@restore')->name('cms.restore');
    });
    Route::post('cms/create', 'Admin\AlpCmsController@store');
    Route::resource('cms', 'Admin\AlpCmsController');

    /*CMS Fin*/


    Route::resource('configuracion', 'Admin\AlpConfiguracionController');

    Route::post('configuracion/storecity', 'Admin\AlpConfiguracionController@storecity')->name('configuracion.storecity');

    Route::post('configuracion/delcity', 'Admin\AlpConfiguracionController@delcity')->name('configuracion.delcity');


    Route::resource('ordenes', 'Admin\AlpOrdenesController');


    Route::group(['prefix' => 'ordenes'], function () {

        Route::get('{id}/delete', 'Admin\AlpOrdenesController@destroy')->name('ordenes.delete');

        Route::get('{id}/detalle', 'Admin\AlpOrdenesController@detalle')->name('ordenes.detalle');

        Route::get('{id}/confirm-delete', 'Admin\AlpOrdenesController@getModalDelete')->name('ordenes.confirm-delete');

        Route::get('{id}/restore', 'Admin\AlpOrdenesController@getRestore')->name('ordenes.restore');

        

        Route::post('/recibir', 'Admin\AlpOrdenesController@recibir')->name('ordenes.recibir');

        Route::post('/aprobar', 'Admin\AlpOrdenesController@aprobar')->name('ordenes.aprobar');

        Route::post('/facturar', 'Admin\AlpOrdenesController@facturar')->name('ordenes.facturar');

        Route::post('/enviar', 'Admin\AlpOrdenesController@enviar')->name('ordenes.enviar');

        Route::get('/empresas/list', 'Admin\AlpOrdenesController@empresas')->name('ordenes.empresas');

        Route::get('/aprobados/list', 'Admin\AlpOrdenesController@aprobados')->name('ordenes.aprobados');

        Route::get('/espera/list', 'Admin\AlpOrdenesController@espera')->name('ordenes.espera');

        Route::get('/recibidos/list', 'Admin\AlpOrdenesController@recibidos')->name('ordenes.recibidos');

        Route::get('/facturados/list', 'Admin\AlpOrdenesController@facturados')->name('ordenes.facturados');

        Route::get('/enviados/list', 'Admin\AlpOrdenesController@enviados')->name('ordenes.enviados');

        Route::get('/cancelados/list', 'Admin\AlpOrdenesController@cancelados')->name('ordenes.cancelados');

        Route::get('/consolidado/list', 'Admin\AlpOrdenesController@consolidado')->name('ordenes.consolidado');

        Route::get('/descuento/list', 'Admin\AlpOrdenesController@descuento')->name('ordenes.descuento');

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

        Route::get('{menu}/edit', 'Admin\AlpMenuController@edit')->name('menus.edit');

        Route::get('{menu}/detalle', 'Admin\AlpMenuController@detalle')->name('menus.detalle');

        Route::get('{menu}/submenu', 'Admin\AlpMenuController@submenu')->name('menus.submenu');

        Route::post('{menu}/storeson', 'Admin\AlpMenuController@storeson')->name('menus.storeson');

        Route::post('{menu}/storesub', 'Admin\AlpMenuController@storesub')->name('menus.storesub');

        Route::get('{menu}/editson', 'Admin\AlpMenuController@editson')->name('menus.editson');

        Route::post('{menu}/updson', 'Admin\AlpMenuController@updson')->name('menus.updson');

    });

    Route::post('menus/create', 'Admin\AlpMenuController@store');



});




//Route::get('mercadopago', ['as'=>'frontend.mercadopago', 'uses'=>'Admin\AlpMercadoPagoController@mercadopago']);



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

Route::get(   'order/pending/',     [
        //'middleware'=>'auth', 
        'as'=>'order.pending', 
        'uses'=>'Admin\AlpCartController@pending'
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

Route::post(    'order/procesarticket',     [ 
        'as'=>'order.procesarticket', 
        'uses'=>'Admin\AlpCartController@orderProcesarTicket'
    ]);

Route::post(    'order/creditcard',     [ 
        'as'=>'order.creditcard', 
        'uses'=>'Admin\AlpCartController@orderCreditcard'
    ]);

Route::post(    'order/getpse',     [ 
        'as'=>'order.getpse', 
        'uses'=>'Admin\AlpCartController@getPse'
    ]);

Route::post(    'order/notificacion',     [ 
        'as'=>'order.notificacion', 
        'uses'=>'Admin\AlpCartController@notificacion'
    ]);


Route::get(    'order/pse',     [ 
        'as'=>'order.pse', 
        'uses'=>'Admin\AlpCartController@orderPse'
    ]);
Route::get(    'order/rapipago',     [ 
        'as'=>'order.rapipago', 
        'uses'=>'Admin\AlpCartController@orderRapipago'
    ]);

//inyeccion de dependencias
Route::bind('product', function($slug){
    return App\Models\AlpProductos::where('slug', $slug)->first();
});

//agregar item al carro
Route::get('cart/add/{product}',['as'=>'cart.add', 'uses'=>'Admin\AlpCartController@add']);

//responde json
Route::get('cart/addtocart/{product}',['as'=>'cart.addtocart', 'uses'=>'Admin\AlpCartController@addtocart']);

Route::post('cart/agregar/',['as'=>'cart.agregar', 'uses'=>'Admin\AlpCartController@addtocart']);

Route::post('cart/agregardetail/',['as'=>'cart.agregardetail', 'uses'=>'Admin\AlpCartController@addtocartdetail']);

//eliminar item del carro
Route::get('cart/delete/{product}',['as'=>'cart.add', 'uses'=>'Admin\AlpCartController@delete']);

//actualizar la cantidad de producto en un carro
Route::get('cart/update/{product}/{cantidad}',['as'=>'cart.update', 'uses'=>'Admin\AlpCartController@update']);

Route::post('cart/updatecart/',['as'=>'cart.updatecart', 'uses'=>'Admin\AlpCartController@updatecart']);

Route::post('cart/updatecantidad/',['as'=>'cart.updatecantidad', 'uses'=>'Admin\AlpCartController@updatecantidad']);

Route::post('cart/delproducto/',['as'=>'cart.delproducto', 'uses'=>'Admin\AlpCartController@delproducto']);

Route::post('cart/botones/',['as'=>'cart.botones', 'uses'=>'Admin\AlpCartController@botones']);

Route::post('cart/updatecartbotones/',['as'=>'cart.updatecartbotones', 'uses'=>'Admin\AlpCartController@updatecartbotones']);

Route::post('cart/updatecartdetalle/',['as'=>'cart.updatecartdetalle', 'uses'=>'Admin\AlpCartController@updatecartdetalle']);

Route::post('cart/getcartbotones/',['as'=>'cart.getcartbotones', 'uses'=>'Admin\AlpCartController@getcartbotones']);

Route::post('cart/addcupon/',['as'=>'cart.addcupon', 'uses'=>'Admin\AlpCartController@addcupon']);




//vaciar un carro
Route::get('cart/vaciar/',['as'=>'cart.vaciar', 'uses'=>'Admin\AlpCartController@vaciar']);

Route::get('cart/detalle/',['as'=>'cart.detalle', 'uses'=>'Admin\AlpCartController@detalle']);
Route::get('cart/detalle2/',['as'=>'cart.detalle2', 'uses'=>'Admin\AlpCartController@detalle2']);

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

Route::get('productos', 'Frontend\ProductosFrontController@all');


Route::get('categorias', ['as' => 'un_categoria', 'uses' => 'Frontend\ProductosFrontController@index']);

Route::get('categoria/{slug}', ['as' => 'categoria', 'uses' => 'Frontend\ProductosFrontController@categorias']);

Route::get('marcas/{slug}', ['as' => 'marcas', 'uses' => 'Frontend\ProductosFrontController@marcas']);

Route::get('paginas/{slug}', ['as' => 'pagina', 'uses' => 'Frontend\ProductosFrontController@cms']);

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


Route::get('clientes/pagar/{orden}',['as'=>'clientes.pagar', 'uses'=>'Frontend\ClientesFrontController@pagar']);

Route::get('clientes/carrito/{carrito}',['as'=>'clientes.carrito', 'uses'=>'Frontend\ClientesFrontController@carrito']);

Route::post('clientes/deleteamigo', 'Frontend\ClientesFrontController@deleteamigo')->name('frontend.clientes.deleteamigo');

Route::post('clientes/storedir/',['as'=>'clientes.storedir', 'uses'=>'Frontend\ClientesFrontController@storedir']);
Route::post('clientes/deldir/',['as'=>'clientes.deldir', 'uses'=>'Frontend\ClientesFrontController@deldir']);



Route::get('miscompras', 'Frontend\ClientesFrontController@miscompras')->name('frontend.clientes.miscompras');

Route::get('misamigos', 'Frontend\ClientesFrontController@misamigos')->name('frontend.clientes.misamigos');

Route::get('miestatus', 'Frontend\ClientesFrontController@miestatus')->name('frontend.clientes.miestatus');

Route::get('misdirecciones', 'Frontend\ClientesFrontController@misdirecciones')->name('frontend.clientes.misdirecciones');


Route::get('clientes/{id}/detalle', 'Frontend\ClientesFrontController@detalle')->name('frontend.clientes.detalle');




Route::post('productos/destacado', ['as'=> 'productos.destacado', 'uses' => 'Admin\AlpProductosController@destacado']);

Route::post('productos/sugerencia', ['as'=> 'productos.sugerencia', 'uses' => 'Admin\AlpProductosController@sugerencia']);

Route::post('productos/desactivar', ['as'=> 'productos.desactivar', 'uses' => 'Admin\AlpProductosController@desactivar']);


Route::post('categorias/destacado', ['as'=> 'categorias.destacado', 'uses' => 'Admin\AlpCategoriasController@destacado']);


Route::get('emailU', function(){
        return new \App\Mail\WelcomeUser('Nombre Cliente', '');
});


Route::get('emailEmbajador', function(){
        return new \App\Mail\WelcomeEmbajador('Nombre Embajador', '');
});

Route::get('aprobado', function(){
        return new \App\Mail\UserAprobado('Nombre Cliente', '');
});

Route::get('emailAfiliado', function(){
        return new \App\Mail\NotificacionAfiliado('Nombre', 'Cliente', 'sdklfjasfasdfasdklfasf', 'Empresa');
});

Route::get('emailAmigo', function(){
        return new \App\Mail\NotificacionAmigo('Nombre', 'Cliente', 'bd60a2c121', 'Embajador Embajador');
});

Route::get('notificacion', function(){
        return new \App\Mail\NotificacionOrden('25', 'La orden 25 Ha sido Enviada!');
});

Route::get('compra', function(){
        return new \App\Mail\CompraRealizada('Nombre', 'Cliente', array('1'=>array('nombre'=>'producto1','precio'=>120,'cantidad'=>1),'2'=>array('nombre'=>'producto2','precio'=>120,'cantidad'=>2),'3'=>array('nombre'=>'producto3','precio'=>120,'cantidad'=>3)) , '25/12/2018');
});




