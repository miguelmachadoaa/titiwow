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

use SoapHeader;
use Illuminate\Support\Facades\Crypt;

use App\Custom\ValidarCuposGo;


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

        dd($c->codigoRta);

    }



    private function conexion(){

       $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://201.234.184.25:8099/api/cupo/ValidarCuposGo?DocumentoEmpleado=79964463');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'apikeyalp2go: 1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);


        //dd($result);
        if (curl_errno($ch)) {
            echo 'Error curl:' . curl_error($ch);
        }
        curl_close($ch);

        $r=json_decode($result);

        return $r;
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
