<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpCarrito;
use App\Models\AlpClientes;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpProductos;
use App\Models\AlpOrdenes;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;
use DB;
use Exception;

use SoapClient;

use SoapHeader;
use Illuminate\Support\Facades\Crypt;

use App\Custom\ValidarCuposGo;


use Illuminate\Console\Command;

class Pruebamail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pruebas:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pruebas mail ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $orden=AlpOrdenes::where('id', '15647')->first();

        $fecha_entrega='2020-01-01';
        

           $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
               ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
               ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
               ->where('alp_ordenes.id', $orden->id)->first();


                $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*',
                  'alp_productos.presentacion_producto as presentacion_producto',
                  'alp_productos.nombre_producto as nombre_producto',
                  'alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
                  ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)
                  ->whereNull('alp_ordenes_detalle.deleted_at')
                  ->get();

                Mail::to('miguelmachadoaa@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));



        
       



    }



    private function conexion(){

       $ch = curl_init();


       $data = array(
        'fechaInicial' => '2021-01-01', 
        'fechaFinal' => '2021-01-30'
      );



        $d = array();

      $d[]=$data;
      #$d['fechaFinal']='2021-01-30';
      #$d['fechaInicial']='2021-01-01';

      $dataraw=json_encode($d);

     // dd($dataraw);

        curl_setopt($ch, CURLOPT_URL, 'https://crearemosdev.com/get360consultar');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'apikeyalp2go: 1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);


       // dd($result);

        if (curl_errno($ch)) {
            echo 'Error curl:' . curl_error($ch);
        }
        curl_close($ch);

        $r=json_decode($result);

        return $r;
    }


    private function masivo(){



      $productos=AlpProductos::get();

     // dd($users);

      $d = array();


      foreach ($productos as $p) {


        #$c=AlpClientes::where('id_user_client', $user->id)->first();

        if (isset($p->id)) {
           $data = array(
          'entryId' =>$p->referencia_producto,
          'referenceName' =>$p->nombre_producto,
          'stock' =>$p->id, 
          'expiredAt' =>$p->created_at,
          'sku' =>$p->referencia_producto,
          'Comments' =>md5($p->created_at)
        );

        $d[]=$data;

        }

      }


      $dataraw=json_encode($d);

      dd($dataraw);

    }






public function makeRequest($endpoint, $jsessionid, $xml, $ignoreResult = false)
{
   // $url = $this->getApiUrl($endpoint, $jsessionid);

    //echo  $endpoint.'<br>';
    
    $xmlObj = new \SimpleXmlElement($xml);

    $request = $xmlObj->asXml();

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);

    $headers = array(
        'Content-Type: text/xml; charset=UTF-8',
        'Content-Length: ' . strlen($request),
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);

    $response = @curl_exec($curl);

    echo  $response;

    if (false === $response) {
        throw new Exception('CURL error: ' . curl_error($curl));
    }

    curl_close($curl);

    if (true === $response || !trim($response)) {
        throw new Exception('Empty response from WCA');
    }

    $xmlResponse = simplexml_load_string($response);

    if (false === $ignoreResult) {
        if (false === isset($xmlResponse->Body->RESULT)) {
            var_dump($xmlResponse);
            throw new Exception('Unexpected response from WCA');
        }

        return $xmlResponse->Body->RESULT;
    }

    return $xmlResponse->Body;
}

        public function getApiUrl($endpoint, $jsessionid)
        {
            return $endpoint . ((null === $jsessionid)
                ? ''
                : ';jsessionid=' . urlencode($jsessionid));
        }

           public function xmlToJson($xml)
          {
              return json_encode($xml);
          }

          public function xmlToArray($xml)
          {
              $json = $this->xmlToJson($xml);
              return json_decode($json, true);
          }







}
