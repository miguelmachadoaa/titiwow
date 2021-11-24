<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpDirecciones;
use App\Models\AlpFeriados;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormasenvio;
use App\Models\AlpImpuestos;

use App\Models\AlpProductos;
use App\Models\AlpAlmacenes;
use App\Models\AlpPagos;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpClientes;

use App\Models\AlpLifeMiles;
use App\Models\AlpLifeMilesCodigos;
use App\Models\AlpLifeMilesOrden;

use App\Models\AlpOrdenesDescuentoIcg;
use App\Models\AlpConsultaIcg;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mail;

use MercadoPago;
use DB;
use Exception;



use Illuminate\Console\Command;

class EnviarLifemiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:lifemiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar codigos lifemiles que no se enviaron en el proceso de compra ';

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

      $date = Carbon::now();


      $d=$date->subDay(3)->format('Y-m-d');


        $ordenes=AlpOrdenes::where('id', '0')->get();


        foreach($ordenes as $orden){


            if($orden->lifemiles_id=='0'){

            }else{
    
                $life=AlpLifemiles::where('id', $orden->lifemiles_id)->first();
    
                $codigos= array();
    
                for ($i=0; $i < $life->cantidad_cupones; $i++) { 
    
                    $codigo=AlpLifeMilesCodigos::where('id_lifemile', '=', $orden->lifemiles_id)->where('estado_registro','1')->first();
    
                    if(isset($codigo->id)){
    
                        $data_lifemiles = array(
                        'id_lifemile' => $codigo->id_lifemile, 
                        'id_codigo' => $codigo->id, 
                        'id_orden' => $orden->id,
                        'id_user' => $orden->id_user
                        );
    
                        AlpLifeMilesOrden::create($data_lifemiles);
    
                        $codigo->update(['estado_registro'=>'0']);
    
                        $codigos[]=$codigo;
    
    
                    }else{
    
                        $mensaje='Gracias por su compra en Alpina Go!, Por su compra usted recibira un Codigo LifeMiles, En estos momentos no tenemos disponible por favor contacte con Nuestra Area de Atencion al Cliente mendiante el Formulario pqr en Nuestra Web.';
                    
                        Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionMensaje($mensaje));
    
                        Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionMensaje($mensaje));
    
                    }
                
                }//endofor

                $user_cliente=User::where('id', $orden->id_user)->first();
    
                $fecha_lm = Carbon::now()->format('m/d/Y');
    
                $this->addlifemiles($user_cliente, $codigos, $fecha_lm);
    
            }

        }


    }//endhadle




    
    private function addlifemiles($user, $cupon, $fecha_lm)
    {

        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();

        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;
        $endpoint = $configuracion->endpoint_ibm;
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        print_r($result);

        $jsessionid = $result['SESSIONID'];

       # echo $jsessionid.' 1<br>';

        $xml='<Envelope><Body><AddRecipient><LIST_ID>8739683</LIST_ID><UPDATE_IF_FOUND>true</UPDATE_IF_FOUND><CREATED_FROM>2</CREATED_FROM><SYNC_FIELDS><SYNC_FIELD><NAME>EMAIL</NAME><VALUE>'.$user->email.'</VALUE></SYNC_FIELD></SYNC_FIELDS><UPDATE_IF_FOUND>true</UPDATE_IF_FOUND><COLUMN><NAME>Nombres</NAME><VALUE>'.$user->first_name.' '.$user->last_name.'</VALUE></COLUMN><COLUMN><NAME>Email</NAME><VALUE>'.$user->email.'</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_Partner_Code</NAME><VALUE>ALPCO</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_Gift_Code</NAME><VALUE>'.$cupon[0]->code.'</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_Gift_Code_Dos</NAME><VALUE>'.$cupon[1]->code.'</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_update_Gift_Code</NAME><VALUE>'.$fecha_lm.'</VALUE></COLUMN><COLUMN><NAME>Fuente</NAME><VALUE>Alpina Go</VALUE></COLUMN></AddRecipient><SendMailing><MailingId>19348098</MailingId><RecipientEmail>'.$user->email.'</RecipientEmail></SendMailing></Body></Envelope>';

        #dd($xml);

        activity()->withProperties($xml)->log('ibm_lifemiles datos enviados ');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result2)->log('ibm_lifemiles respuesta');

        print_r($result2);

        echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

             // activity()->withProperties($result)->log('codigo-descuento-xml_ibm_result2');

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

        //throw new Exception('CURL error: ' . curl_error($curl));
        //
        return false;

    }

    curl_close($curl);

    if (true === $response || !trim($response)) {
       // throw new Exception('Empty response from WCA');
       // 
       $response=false;
       return false;
    }


    if ($response==false) {
      $xmlResponse = false;
    }else{
      $xmlResponse = simplexml_load_string($response);
    }

    if (false === $ignoreResult) {

        if (false === isset($xmlResponse->Body->RESULT)) {

           // var_dump($xmlResponse);

            //throw new Exception('Unexpected response from WCA');
            //
            return false;

        }else{

          return $xmlResponse->Body->RESULT;
        }

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






}//endclass