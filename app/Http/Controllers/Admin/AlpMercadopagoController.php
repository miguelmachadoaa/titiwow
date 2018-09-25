<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
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

      

      $preference_data = [
      "items" => [
        [
          "id" => '1234',
          "title" => 'Titulo del articulo',
          "description" => 'Descripcion del articulo',
          "picture_url" => 'Imagen del articulo',
          "quantity" => 1,
          "currency_id" => 'COP',
          "unit_price" => 10.00
           ]
      ],
      "payer" => [
        "email" => 'miguelmachadoaa@gmail.com'
      ]
    ];
    $preference = MP::post("/checkout/preferences",$preference_data);
    return dd($preference);


    }





    
}
