<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpCarrito;
use App\Models\AlpCarritoDetalle;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;
use DB;
use Exception;


use Illuminate\Console\Command;

class NotificacionCarrito extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:carrito';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificacion de usuarios que dejan carritos abandonados ';

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
        //
        $configuracion=AlpConfiguracion::where('id', '1')->first();

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $fecha_hoy=$date->format('d/m/Y');


        $carritos =  DB::table('alp_carrito')->select('alp_carrito.*','users.first_name as first_name','users.last_name as last_name','users.email as email')
          ->join('users','alp_carrito.id_user' , '=', 'users.id')
          ->limit('10')
         ->where('alp_carrito.notificacion','=', 0)
          ->get();


         // dd($carritos);


        activity()->withProperties($carritos)->log('carritos');


      foreach ($carritos  as $car) {

        $date = Carbon::parse($car->created_at); 

        $now = Carbon::now();

        $diff = $date->diffInHours($now); 

        //dd($diff);

        if ($diff>24) {
            # code...

        activity()->withProperties($car)->log('car');


        $detalles =  DB::table('alp_carrito_detalle')->select('alp_carrito_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.precio_base as precio_base')
          ->join('alp_productos','alp_carrito_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_carrito_detalle.id_carrito', $car->id)->get();

          //dd($detalles);

          Mail::to($car->email)->send(new \App\Mail\NotificacionCarrito($car, $detalles, $configuracion));

         Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionCarrito($car, $detalles, $configuracion));


           $this->addibm($car, $detalles, $fecha_hoy);

            $arrayName = array('notificacion' => 1 );

            $ord=AlpCarrito::where('id', $car->id)->first();

            $ord->update($arrayName);

        }



      }



    }


    private function addibm($user, $cart, $fecha)
    {
        
        $pod = 0;
        $username = 'api_alpina@alpina.com';
        $password = 'Alpina2020!';

        $endpoint = "https://api2.ibmmarketingcloud.com/XMLAPI";
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

       // print_r($result);

        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';


            $rows='';

            foreach ($cart as $d) {

              $rows=$rows.'<ROW>
      <COLUMN name="Correo">
      <![CDATA['.$user->email.']]>
      </COLUMN>

      <COLUMN name="Referencia_producto">
      <![CDATA['.$d->referencia_producto.']]>
      </COLUMN>

      <COLUMN name="Nombre_producto">

      <![CDATA['.$d->nombre_producto.']]>
      </COLUMN>

      <COLUMN name="Precio_Unitario">
      <![CDATA['.$d->precio_base.']]>
      </COLUMN>

      <COLUMN name="Cantidad">
      <![CDATA['.$d->cantidad.']]>
      </COLUMN>

      <COLUMN name="Imagen_producto">

      <![CDATA['.secure_url('uploads/productos/'.$d->imagen_producto).']]>
      </COLUMN>

      <COLUMN name="Fecha_carrito">
      <![CDATA['.$fecha.']]>
      </COLUMN>
      
    </ROW>';
              
            }


            $xml='<Envelope>
  <Body>
  <InsertUpdateRelationalTable>
  <TABLE_ID>10843849</TABLE_ID>
  <ROWS>
    '.$rows.'
  </ROWS>
  </InsertUpdateRelationalTable>
  </Body>
</Envelope>';


            activity()->withProperties($xml)->log('xml_ibm_add_recipiente');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result)->log('xml_ibm_add_result');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

              activity()->withProperties($result)->log('xml_ibm_add_result2');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

              return 'FALSE';

          }
    }



public function makeRequest($endpoint, $jsessionid, $xml, $ignoreResult = false)
{
    $url = $this->getApiUrl($endpoint, $jsessionid);

    echo  $url.'<br>';
    
    $xmlObj = new \SimpleXmlElement($xml);

    $request = $xmlObj->asXml();

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
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
