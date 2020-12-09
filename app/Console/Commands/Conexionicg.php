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

use Illuminate\Support\Facades\Crypt;


use Illuminate\Console\Command;

class Conexionicg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conexion:icg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Conexion icg ';

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
        
        $c=$this->conexion();

        dd($c);

    }


    private function conexion()
    {
        
        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;
        $endpoint = 'http://201.234.184.25:8099/wsALP2.asmx';
        $jsessionid = null;

       // $baseXml = '%s';
     //   $loginXml = '';
     //   $getListsXml = '%s%s';
     //   $logoutXml = '';

        $date = Carbon::now();

        $hoy=$date->format('Ymdh:m:s');

        $fechad=$date->format('Y-m-d');
        $fechah=$date->format('h:m:s');
        $fecha=$fechad.' '.$fechah;

        $d='#.Za('.$hoy.'!4$k;';

        $p='Q@4;_'.$hoy.'?rK&%)';

        while (strlen($d) < 32) {
          $d='0'.$d;
        }

        while (strlen($p) < 32) {
          $p='0'.$p;
        }

      // dd($d);

        $newEncrypter = new \Illuminate\Encryption\Encrypter( $p, 'AES-256-CBC' );
        $encrypted_user = $newEncrypter->encrypt( 'test1' );
        //$encrypted_user =  Crypt::encrypt('test1');


        $newEncrypter2 = new \Illuminate\Encryption\Encrypter( $d, 'AES-256-CBC' );
        $encrypted_password = $newEncrypter->encrypt( 'test2' );

       

        $xml='<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope/" soap:encodingStyle="http://www.w3.org/2003/05/soap-encoding">';
          $xml=$xml.'<soap:Header>';
            $xml=$xml.'<tem:LoginInfo xmlns:tem="http://www.w3.org/2003/05/logininfo/" ><tem:UserName>'.$encrypted_user.'</tem:UserName>';
              $xml=$xml.'<tem:Password>'.$encrypted_password.'</tem:Password>';
              $xml=$xml.'<tem:Fecha>'.$fecha.'</tem:Fecha>';
            $xml=$xml.'</tem:LoginInfo>';
          $xml=$xml.'</soap:Header>';
        $xml=$xml.'<soap:Body>';
          $xml=$xml.'<tem:RegistroConsumo xmlns:tem="http://www.w3.org/2003/05/registroconsumo/">';
            $xml=$xml.'<tem:NumeroPedido>ALP1000</tem:NumeroPedido>';
           $xml=$xml.' <tem:Fecha>2020-12-04T11:36:36</tem:Fecha>';
            $xml=$xml.'<tem:DocumentoEmpleado>12312312</tem:DocumentoEmpleado>';
            $xml=$xml.'<tem:FormaPago>Contado</tem:FormaPago>';
            $xml=$xml.'<tem:ValorTransaccion>100000</tem:ValorTransaccion>';
            $xml=$xml.'<tem:ValorDescuento>100000</tem:ValorDescuento>';
            $xml=$xml.'<tem:ReferenciaRegistradaAnulado>100000</tem:ReferenciaRegistradaAnulado>';
          $xml=$xml.'</tem:RegistroConsumo>';
        $xml=$xml.'</soap:Body>';
        $xml=$xml.'</soap:Envelope>';





        echo $xml;

        echo '-------';

       


        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        dd($result);


        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';


            $rows='';


            foreach ($cart as $d) {

               // dd($d);

              $rows=$rows.'<ROW> <COLUMN name="Correo"> <![CDATA['.$user->email.']]> </COLUMN><COLUMN name="Referencia_producto"> <![CDATA['.$d->referencia_producto.']]> </COLUMN><COLUMN name="Nombre_producto"><![CDATA['.$d->nombre_producto.']]> </COLUMN><COLUMN name="Precio_Unitario"> <![CDATA['.number_format($d->precio_base,0).']]> </COLUMN><COLUMN name="Cantidad"> <![CDATA['.$d->cantidad.']]> </COLUMN><COLUMN name="Imagen_producto"><![CDATA['.secure_url('uploads/productos/'.$d->imagen_producto).']]> </COLUMN><COLUMN name="Url_producto"><![CDATA['.secure_url('/productos/'.$d->slug).']]></COLUMN><COLUMN name="Valor_por_gramo_producto"><![CDATA['.number_format($d->precio_base/$d->cantidad,0).']]> </COLUMN><COLUMN name="Fecha_carrito"> <![CDATA['.$fecha.']]> </COLUMN></ROW>'; 

             // dd($rows);



            }


            $xml='<Envelope><Body><InsertUpdateRelationalTable><TABLE_ID>10843849</TABLE_ID><ROWS>'.$rows.'</ROWS></InsertUpdateRelationalTable></Body></Envelope>';


            activity()->withProperties($xml)->log('carrito-xml');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result2)->log('carrito-result');


        $xml = '<Envelope><Body><Logout/></Body></Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

              activity()->withProperties($result)->log('xml_ibm_add_result2-carrito');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          
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
