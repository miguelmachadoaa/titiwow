<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpConfiguracion;
use DB;
use View;
use MP;

class AlpMercadopagoController extends JoshController
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
   

    public function mercadopago()
    {

     $configuracion = AlpConfiguracion::where('id', '1')->first();

      //echo $configuracion->id_mercadopago;
      //echo "<br>";
      //echo $configuracion->key_mercadopago;

      $mp = new MP ();

     // $mp::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

     // $access_token = $mp::getAccessToken();

     //dd($access_token);
      // MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);



     /* $preference_data = [
      "items" => [
        [
          "id" => '1234',
          "title" => 'Titulo del articulo',
          "description" => 'Descripcion del articulo',
          "picture_url" => 'uno.png',
          "quantity" => 1,
          "currency_id" => 'USD',
          "unit_price" => 10.00
           ]
      ],
      "payer" => [
        "email" => 'miguelmachadoaa@gmail.com'
      ]
    ];*/



    $body = array(
      "json_data" => array(
        "siteId" => "MCO"
      )
    );



    $preference = MP::post('/users/test_user', $body);
     dd($preference);


    }





    
}
