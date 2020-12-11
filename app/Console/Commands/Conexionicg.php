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

use SoapClient;

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
        $endpoint = 'http://201.234.184.25:8099/wsALP2.asmx?WSDL';
        $jsessionid = null;

       // $baseXml = '%s';
     //   $loginXml = '';
     //   $getListsXml = '%s%s';
     //   $logoutXml = '';

        $date = Carbon::now();

        $hoy=$date->format('YmdH:m:s');

        //$fechad=$date->format('Ymd');
        $fechad='20201020';
        //$fechadt=$date->format('Y-m-d');
        $fechadt=$date->format('Y-m-d');
        //$fechah=$date->format('H:m:s');
        $fechah='10:59:50';
        $fecha=$fechad.' '.$fechah;
        $fecha_cont=$fechadt.'T'.$fechah;

        $d='#.Za('.$fechad.$fechah.'!4$k;';

        $p='Q@4;_'.$fechah.$fechad.'?rK&%';

        while (strlen($d) < 32) {
          $d='0'.$d;
        }

        while (strlen($p) < 32) {
          $p='0'.$p;
        }

       echo $d;
       echo "-------";

        $newEncrypter = new \Illuminate\Support\Facades\Crypt($d, 'AES-256-CBC');
        $encrypted_user = $newEncrypter::encryptString('test1');
       // $encrypted_user=substr($encrypted_user, 0, 32);
        //$encrypted_user =  Crypt::encrypt('test1');
        //
        echo  $encrypted_user;
        echo "-------";


        $newEncrypter2 = new \Illuminate\Support\Facades\Crypt($p, 'AES-256-CBC');
        $encrypted_password = $newEncrypter::encryptString('test2');

         echo $p;
       echo "-------";

        echo  $encrypted_password;
        echo "-------";


        $plaintext = '1234';
        $cipher = 'AES-256-CBC';
        $key = 'this is a bad key';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

       $encrypted = openssl_encrypt('test1', $cipher, $encrypted_password, 0, $iv);

       echo "respuesta";

        dd($encrypted);
       



       // $encrypted_password=substr($encrypted_password, 0, 32);

      //  dd($encrypted_password);

      //Registrar Consumo

        /*$xml='<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope/" soap:encodingStyle="http://www.w3.org/2003/05/soap-encoding">';
          $xml=$xml.'<soap:Header>';
            $xml=$xml.'<tem:LoginInfo xmlns:tem="http://www.w3.org/2003/05/logininfo/" ><tem:UserName>'.$encrypted_user.'</tem:UserName>';
              $xml=$xml.'<tem:Password>'.$encrypted_password.'</tem:Password>';
              $xml=$xml.'<tem:Fecha>'.$fecha.'</tem:Fecha>';
            $xml=$xml.'</tem:LoginInfo>';
          $xml=$xml.'</soap:Header>';
        $xml=$xml.'<soap:Body>';
          $xml=$xml.'<tem:RegistroConsumoGo xmlns:tem="http://www.w3.org/2003/05/registroconsumogo/">';
            $xml=$xml.'<tem:NumeroPedido>ALP1024</tem:NumeroPedido>';
           $xml=$xml.' <tem:Fecha>'.$fecha_cont.'</tem:Fecha>';
            $xml=$xml.'<tem:DocumentoEmpleado>1020822917</tem:DocumentoEmpleado>';
            $xml=$xml.'<tem:FormaPago>Contado</tem:FormaPago>';
            $xml=$xml.'<tem:ValorTransaccion>100000</tem:ValorTransaccion>';
            $xml=$xml.'<tem:ValorDescuento>100000</tem:ValorDescuento>';
            //$xml=$xml.'<tem:ReferenciaRegistradaAnulado>100000</tem:ReferenciaRegistradaAnulado>';
          $xml=$xml.'</tem:RegistroConsumo>';
        $xml=$xml.'</soap:Body>';
        $xml=$xml.'</soap:Envelope>';*/

       // dd($xml);

        //validar Cupo



         $xml=' <soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/">';
          $xml=$xml.'<soap:Header>';
            $xml=$xml.'<tem:LoginInfo><tem:UserName>'.$encrypted_user.'</tem:UserName>';
              $xml=$xml.'<tem:Password>'.$encrypted_password.'</tem:Password>';
              $xml=$xml.'<tem:Fecha>'.$fecha.'</tem:Fecha>';
            $xml=$xml.'</tem:LoginInfo>';
          $xml=$xml.'</soap:Header>';
        $xml=$xml.'<soap:Body>';
          $xml=$xml.'<tem:ValidarCuposGo>';
            $xml=$xml.'<tem:DocumentoEmpleado>79964463</tem:DocumentoEmpleado>';
          $xml=$xml.'</tem:ValidarCuposGo>';
        $xml=$xml.'</soap:Body>';
        $xml=$xml.'</soap:Envelope>';


        echo $xml;

        echo '-------';

       // $client = new \SoapClient($wsdl, $options);

        $client = new SoapClient($endpoint);

        $result = $client->ValidarCuposGO($xml);
        //$result = $client->RegistrarConsumoGo($xml);

        dd($result);
          
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