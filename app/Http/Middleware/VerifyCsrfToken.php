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
       'ordenes/entregar', //
       'order/procesar', //
       'order/procesarticket', //
       'order/procesarbono', //
       'order/creditcard', //
       'pedidos/order/creditcard', //
       'order/pse', //
       'order/getpse', //
       'order/notificacion', //
       'order/rapipago', //
       'cart/addcupon', //
       'tomapedidos/addcupon', //
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
       'cart/addtocartancheta', //
       'cart/deltocartancheta', //
       'cart/agregarunaancheta', //
       'tomapedidos/agregarunaancheta', //
       'admin/tomapedidos/agregarunaancheta', //
       'cart/verificarancheta', //
       'formasenvio/storecity', //
       'formasenvio/delcity', //
       'configuracion/setbarrio', //
       'configuracion/storecity', //
       'configuracion/delcity', //
       'configuracion/verificarciudad', //
       'productos/addrelacionado', //
       'departamentos/addusuario', //
       'departamentos/delusuario', //
       'ticket/storerespuesta', //
       'ticket/departamento', //
       'ticket/urgencia', //
       'delamigo', //
       'compramas', //
       'compramasinventario', //
       'get360', //
       'get360consultar', //
       'get360actualizar' //
    ];
}
