<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
       'cart/addcupon', //
       'productos/verificar/referenciasap', //
       'cart/storedir', //
       'admin/ordenes/storeconfirm', //
       'cart/verificarDireccion', //
       'clientes/storedir', //
       'clientes/deldir', //
       'storeamigo', //
       'empresas/storeamigo', //
       'clientes/deleteamigo', //
       'clientes/eliminar', //
       'admin/clientes/eliminar', //
       'empresas/delamigo', //
       'cart/updatecart', //
       'cart/updatecantidad', //
       'cart/delproducto', //
       'cart/updatecartbotones', //
       'cart/botones', //
       'cart/detalle', //
       'formasenvio/storecity', //
       'formasenvio/delcity', //
       'configuracion/storecity', //
       'configuracion/delcity', //
       'configuracion/verificarciudad', //
       'delamigo' //
    ];
}
