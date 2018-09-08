<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpProductos;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasProductos;
use App\Models\AlpInventario;
use App\Models\AlpMarcas;
use App\Http\Requests;
use App\Http\Requests\ProductosRequest;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;
use MP;


class AlpCartController extends JoshController
{


    private $tags;

    public function __construct()
    {
        parent::__construct();

        if (!\Session::has('cart')) {
          \Session::put('cart', array());
        }
       
    }


    public function generatePaymentGateway()
    {
        

      
        $mp = new MP ('7310103649683437', 'Uu5SD5VoVM7TbXOaqM8XDUkeBu45EZxK');

        $current_user = Sentinel::getUser();

       $cart= \Session::get('cart');

       $preference_data = array (
        "items" => array (
              array (
                  "title" => "Test",
                  "quantity" => 1,
                  "currency_id" => "USD",
                  "unit_price" => 10.4
              )
          )
      );

        $preference = $mp::create_preference($preference_data);
        // also you can use try-catch for create the preference, DB transaction for the whole generatePaymentGateway method, etc...

        // finally return init point to be redirected - or
        // sandbox_init_point
        return $preference['response']['sandbox_init_point'];
    }


     /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show()
    {
       $cart= \Session::get('cart');

       $total=$this->total();

      return view('frontend.cart', compact('cart', 'total'));
    }

     public function mercadopago()
    {
             
      return redirect()->to($this->generatePaymentGateway());

    }

    public function orderDetail()
    {
       $cart= \Session::get('cart');

       $total=$this->total();


       if(count($cart)<=0){

          return redirect('productos');

       }else{

          return view('frontend.order.detail', compact('cart', 'total'));


       }


    }

    public function add( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $producto->cantidad=1;

       $producto->precio=rand ( 1 , 100 );

       $cart[$producto->slug]=$producto;

      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function delete( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

      unset( $cart[$producto->slug]);
       

      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function update( AlpProductos $producto, $cantidad)
    {
       $cart= \Session::get('cart');

       $cart[$producto->slug]->cantidad=$cantidad;
       

      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function vaciar( )
    {
       $cart= \Session::forget('cart');

      
       return redirect('cart/show');

      
    }

    private function total()
    {
       $cart= \Session::get('cart');

      $total=0;

      foreach($cart as $row) {

        $total=$total+($row->cantidad*$row->precio);

      }

       return $total;

      
    }




    
}
