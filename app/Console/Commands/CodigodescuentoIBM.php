<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpDetalles;
use App\Models\AlpInventario;
use App\Models\AlpConfiguracion;
use App\Models\AlpSaldo;
use App\Models\AlpCupones;
use App\Models\AlpCuponesUser;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;

use Illuminate\Console\Command;

class CodigodescuentoIBM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codigodescuento:ibm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LLevar el saldo vencido a 0';

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


        

        //dd($date_inicio.' '.$date_fin);

        $date = Carbon::now();

        $d=$date->subDay(15)->format('Y-m-d');
       // $d=$date->subDay(100)->format('Y-m-d');
      
        $users=User::whereDate('created_at','=', $d)->get();

        $i=0;

        //dd($users);

        foreach ($users as $u) {

            $orden=AlpOrdenes::where('id_cliente', $u->id)->orderBy('id', 'desc')->first();

            if (isset($orden->id)) {}else{

                    $codigo=strtoupper(substr(md5(time()), 0,12));

                    $date_inicio = Carbon::now()->format('Y-m-d');
                    $date_fecha = Carbon::now()->format('m/d/Y');

                    $date_fin = Carbon::now()->addDay(30)->format('Y-m-d');

                    $data = array(
                        'codigo_cupon' => $codigo, 
                        'valor_cupon' =>  '20', 
                        'tipo_reduccion' => '2', 
                        'limite_uso' => '1', 
                        'limite_uso_persona' => '1', 
                        'fecha_inicio' => $date_inicio, 
                        'fecha_final' => $date_fin, 
                        'monto_minimo' =>'20000', 
                        'maximo_productos' =>'6', 
                        'primeracompra' => '0', 
                        'id_user' =>'1'
                    );
                     
                    $cupon=AlpCupones::create($data);

                   // dd($cupon);

                    $datac = array(
                        'id_cupon' => $cupon->id, 
                        'id_cliente' => $u->id, 
                        'condicion' => '1' 
                    );

                    AlpCuponesUser::create($datac);

                       $this->addibm($u, $cupon, $date_fecha);


            }

        }

    }


    private function addibm($user, $cupon, $fecha)
    {
        
        $pod = 0;
        $username = 'api_alpina@alpina.com';
        $password = 'Alpina2020!';

        $endpoint = "https://api-campaign-us-2.goacoustic.com/XMLAPI";
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

            $xml='<Envelope><Body><AddRecipient><LIST_ID>8739683</LIST_ID><SYNC_FIELDS><SYNC_FIELD><NAME>EMAIL</NAME><VALUE>'.$user->email.'</VALUE></SYNC_FIELD></SYNC_FIELDS><UPDATE_IF_FOUND>true</UPDATE_IF_FOUND><COLUMN><NAME>Email</NAME><VALUE>'.$user->email.'</VALUE></COLUMN><COLUMN><NAME>Codigo_cupon_ecommerce</NAME><VALUE>'.$cupon->codigo_cupon.'</VALUE></COLUMN><COLUMN><NAME>Fecha_cupon_ecommerce</NAME><VALUE>'.$fecha.'</VALUE></COLUMN></AddRecipient></Body></Envelope>';


           activity()->withProperties($xml)->log('codigo-descuento-xml_ibm_add_recipiente-codigodescuento');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result)->log('codigo-descuento-xml_ibm_add_result-codigodescuento');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

              activity()->withProperties($result)->log('codigo-descuento-xml_ibm_add_result2-codigodescuento');

             print_r($result);

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