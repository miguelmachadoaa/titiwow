<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCarrito;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class CarritoExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {

       $carrito= AlpCarrito::select(
          'alp_carrito.id as id', 
          'alp_carrito.id_user as id_user', 
          'users.first_name as first_name',
          'users.last_name as last_name',
          'users.email as email',
           DB::raw('DATE_FORMAT(alp_carrito.created_at, "%d/%m/%Y")  as fecha'),
          'users.email as email')
          ->leftJoin('users', 'alp_carrito.id_user', '=', 'users.id')
          
          ->whereDate('alp_carrito.created_at', '>=', $this->desde)
          ->whereDate('alp_carrito.created_at', '<=', $this->hasta)->get();



          $cart = array();



          foreach ($carrito as $c) {

            $detalles= AlpCarritoDetalle::select( 
            'alp_productos.nombre_producto as nombre_producto',
            'alp_carrito_detalle.id_carrito as id_carrito',
             DB::raw('sum(alp_carrito_detalle.cantidad*alp_productos.precio_base)  as monto'),
            'alp_carrito_detalle.cantidad as cantidad')
            ->join('alp_productos', 'alp_carrito_detalle.id_producto', '=', 'alp_productos.id')
            ->where('alp_carrito_detalle.id_carrito', '=', $c->id)
            ->whereDate('alp_carrito_detalle.created_at', '>=', $this->desde)
            ->whereDate('alp_carrito_detalle.created_at', '<=', $this->hasta)->get();

              

              $monto=0;
              $prod='';

              foreach ($detalles as $detalle) {
                
                $monto=$detalle->monto+$monto;
                $prod=$prod.' ,'.$detalle->nombre_producto;

              }


              $c->total_venta=$monto;
              $c->nombre_productos=$prod;

              $cart[]=$c;
            
          }

       
       // dd($cart);

          //dd($ordenes);

        return view('admin.exports.carrito', [
            'carrito' => $cart
        ]);
    }
}