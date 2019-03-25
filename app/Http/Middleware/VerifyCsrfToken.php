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
       'order/procesar', //
       'order/procesarticket', //
       'order/creditcard', //
       'order/pse', //
       'order/getpse', //
       'order/notificacion', //
       'order/rapipago', //
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
       'admin/clientes/eliminar/cliente', //
       'empresas/delamigo', //
       'cart/updatecart', //
       'cart/updatecartsingle', //
       'cart/updatecantidad', //
       'cart/delproducto', //
       'cart/updatecartbotones', //
       'cart/updatecartbotonessingle', //
       'cart/updatecartdetalle', //
       'cart/getcartbotones', //
       'cart/botones', //
       'cart/detalle', //
       'cart/agregar', //
       'cart/agregarsingle', //
       'cart/agregardetail', //
       'formasenvio/storecity', //
       'formasenvio/delcity', //
       'configuracion/storecity', //
       'configuracion/delcity', //
       'configuracion/verificarciudad', //
       'productos/addrelacionado', //
       'delamigo' //
    ];
}
