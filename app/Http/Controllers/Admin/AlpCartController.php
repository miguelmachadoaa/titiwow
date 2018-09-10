<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpProductos;
use App\Models\AlpDirecciones;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasProductos;
use App\Models\AlpInventario;
use App\Models\AlpMarcas;
use App\Models\AlpFormasenvio;
use App\Models\AlpFormaspago;
use App\Http\Requests;
use App\Http\Requests\ProductosRequest;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;


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

     

    public function orderDetail()
    {
       $cart= \Session::get('cart');

      $total=$this->total();

      $user_id = Sentinel::getUser()->id;

      

      $direcciones = AlpDirecciones::where('id_client', $user_id)->get();

      $formasenvio = AlpFormasenvio::all();

      $formaspago = AlpFormaspago::all();



       if(count($cart)<=0){

          return redirect('productos');

       }else{

          return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago'));


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

    public function addtocart( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $producto->cantidad=1;

       $producto->precio=rand ( 1 , 100 );

       $cart[$producto->slug]=$producto;

      // return $cart;

       \Session::put('cart', $cart);

       $cantidad=$this->cantidad();

       $data = array(
        'resultado' => 1, 
        'contenido' => $cantidad.' Items'
      );

       return $data;
      
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

    private function cantidad()
    {
       $cart= \Session::get('cart');

      $cantidad=0;

      foreach($cart as $row) {

        $cantidad=$cantidad+($row->cantidad);

      }

       return $cantidad;

      
    }


     public function storedir(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);

        $input['id_user']=$user_id;
        $input['id_client']=$user_id;
               
         
        $direccion=AlpDirecciones::create($input);


        if ($direccion->id) {

          return redirect('order/detail');
            

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }

    public function setdir( $id)
    {

      $user_id = Sentinel::getUser()->id;

      $direcciones=AlpDirecciones::where('id_client', $user_id)->get();

      $data = array('default_address' => '0' );

        foreach ($direcciones as $dir) {
          
          $dir_upd = AlpDirecciones::find($dir->id);

          $dir_upd->update($data);


        }

      $data = array('default_address' => '1' );


          $direccion= AlpDirecciones::find($id);

          $direccion->update($data);

        if ($direccion->id) {

          return redirect('order/detail');
            

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }

    public function deldir( $id)
    {

      $user_id = Sentinel::getUser()->id;

      


          $direccion= AlpDirecciones::find($id);

          $direccion->delete();

       

          return redirect('order/detail');
            
   

    }
    
}
