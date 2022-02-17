<?php

namespace App\Http\Livewire;




use Livewire\Component;
use App\Models\AlpConfiguracion;
use App\Models\AlpAlmacenes;
use App\Models\AlpFormaspago;
use App\Models\AlpDetalles;
use App\Models\AlpProductos;
use App\User;
use App\Models\AlpDirecciones;
use App\Models\AlpOrdenesDescuentoIcg;
use App\Models\AlpOrdenes;
use App\RoleUser;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use Livewire\WithPagination;
use DB;
use Sentinel;
use Illuminate\Support\Facades\Log;
use Mail;
use MercadoPago;


class EsperaList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $showModal=false;

    public $search ="";

    public $idCancelar ="";

    public $cantid = 10;

    public $sortBy = 'id';
    
    public $sortAsc = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => false]
    ];

    public function render()
    {

        
        if (Sentinel::check()) { 

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }

        $user = Sentinel::getUser();

        if (isset($user->id)) {
  
  
          if ($user->almacen=='0') {

            $enespera = AlpOrdenes::when($this->search, function($query){
                return $query->where(function ($query){
                    $query->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $this->search . '%')
                    ->orwhere('alp_ordenes.referencia','like','%'.$this->search.'%')
                    ->orWhere('alp_ordenes.created_at','like','%'.$this->search.'%');
                });
            }) 
            ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
            ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
            ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
            ->join('config_cities', 'alp_almacenes.id_city', '=', 'config_cities.id')
            ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
            ->select(
                'alp_ordenes.id as id',
                'alp_ordenes.origen as origen', 
                'alp_ordenes.id_almacen',
                'alp_ordenes.id_forma_pago',
                'alp_ordenes.estatus_pago as estatus_pago', 
                'alp_ordenes.monto_total as monto_total', 
                'alp_ordenes.referencia as referencia', 
                'alp_ordenes.created_at as created_at', 
                'users.first_name as first_name', 
                'users.last_name as last_name',
                'alp_clientes.telefono_cliente as telefono_cliente', 
                'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
                'alp_almacenes.nombre_almacen as nombre_almacen',
                'config_cities.city_name as city_name',
                'alp_formas_envios.nombre_forma_envios as forma_envio',
                'alp_formas_pagos.nombre_forma_pago as forma_pago')
            ->where('alp_ordenes.estatus', '8')
            ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');
        }else{
            
            $enespera = AlpOrdenes::when($this->search, function($query){
                return $query->where(function ($query){
                    $query->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $this->search . '%')
                    ->orwhere('alp_ordenes.referencia','like','%'.$this->search.'%')
                    ->orWhere('alp_ordenes.created_at','like','%'.$this->search.'%');
                });
            }) 
            ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
            ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
            ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
            ->join('config_cities', 'alp_almacenes.id_city', '=', 'config_cities.id')
            ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
            ->select(
                'alp_ordenes.id as id',
                'alp_ordenes.origen as origen', 
                'alp_ordenes.id_almacen',
                'alp_ordenes.estatus_pago as estatus_pago', 
                'alp_ordenes.monto_total as monto_total', 
                'alp_ordenes.referencia as referencia', 
                'alp_ordenes.created_at as created_at', 
                'users.first_name as first_name', 
                'users.last_name as last_name',
                'alp_clientes.telefono_cliente as telefono_cliente', 
                'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
                'alp_almacenes.nombre_almacen as nombre_almacen',
                'config_cities.city_name as city_name',
                'alp_formas_envios.nombre_forma_envios as forma_envio',
                'alp_formas_pagos.nombre_forma_pago as forma_pago')
            ->where('alp_ordenes.estatus', '8')
            ->where('alp_ordenes.id_almacen', '=', $user->almacen)
            ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        }
    }

        
        $enespera = $enespera->paginate($this->cantid);

        foreach($enespera as $row){
            
            $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {
                $row->codigo_cupon=$descuento->codigo_cupon;

              }else{

                $row->codigo_cupon='N/A';

              }
        }

        return view('livewire.espera-list',[
            'idCancelar' => $this->idCancelar,
            'showModal' => $this->showModal,
            'enespera' => $enespera,
            'id_rol' => $id_rol
        ]);


    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy( $field)
    {
        if($field == $this->sortBy){
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function modal(){

        if($this->showModal){

            $this->showModa=false;

        }else{

            $this->showModa=true;
        }
    }


    public function selectedcancelar($id){

        $this->idCancelar=$id;
        $this->showModal=true;
     //   $this->dispatchBrowserEvent('openModal');
        $this->emit('openModal');

    }

    public function cancelar(){

     //   $this->CancelarOrdenCompramas();
        $this->CancelarMercadopago($this->idCancelar);

    }





    
       
public function CancelarOrdenCompramas()
{


  #echo 'proceso envio a velocity   / ';

    $id_orden=$this->idCancelar;

  $configuracion=AlpConfiguracion::first();
  
  $orden=AlpOrdenes::where('id', $id_orden)->first();

 # echo $orden->id.'   / ';

  $almacen_pedido=AlpAlmacenes::where('id', $orden->id_almacen)->first();
  
  $formapago=AlpFormaspago::where('id', $orden->id_forma_pago)->first();

    //Log::info('compramas orden '.json_encode($orden));

  

           


    $dataraw=json_encode('');

  #  echo "data / ".$dataraw;

    $url= 'https://ff.logystix.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel';

   # echo $dataraw.' - ';

    //$orden->update(['send_json_masc'=>$dataraw]);

    Log::info($dataraw);

    activity()->withProperties($dataraw)->log('Envio Aprobado Velocity '.$orden->id.' .vp634');

  $ch = curl_init();

  #curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago?warehouse_id='.$almacen->codigo_almacen);
  #curl_setopt($ch, CURLOPT_URL, 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');
  curl_setopt($ch, CURLOPT_URL, 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  #curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  
 // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

  $headers = array();
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Woobsing-Token: '.$configuracion->compramas_token;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);

  #$result='';
  
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  $res=json_decode($result);

   Log::info('Datos de respuesta  a registro  de orden cancelada manualmente  en Velocity orden id '.$orden->id.' .el426'.json_encode($res));
   
   activity()->withProperties($res)->log('Datos de respuesta  a registro  de orden cancelada manualmente  en Velocity orden id '.$orden->id.' . el428');

   $notas='Cancelación de orden en Velocity Manual.';


   if (isset($res->mensaje)) {
     $notas=$notas.$res->mensaje.' ';
   }

   if (isset($res->codigo)) {
     $notas=$notas.$res->codigo.' ';
   }

   

   if (isset($res->message)) {
     $notas=$notas.$res->message.' ';
   }

   if (isset($res->causa->message)) {
     $notas=$notas.$res->causa->message.' ';
   }


   $notas=$notas.'Codigo: VP.';

         $dtt = array(
            'json' => $result,
            'estado_compramas' => '3'
            
          );

        $orden->update($dtt);

        $texto='Orden Cancelada manualmente';

        $data_history = array(
            'id_orden' => $orden->id, 
            'id_status' => '9', 
            'notas' => $notas, 
            'json' => json_encode($result), 
           'id_user' => 1
        );

        $history=AlpOrdenesHistory::create($data_history);


        $ord=AlpOrdenes::where('id', $orden->id)->first();

        $arrayName = array('estatus' => 4, 'estatus_pago'=>3 );

        $ord->update($arrayName);


        try {
          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

       Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
          
        } catch (\Exception $e) {

          activity()->withProperties(1)
                    ->log('error envio de correo');
          
        }
      


}




public function cancelarMercadopago($id_orden){

  $orden=AlpOrdenes::where('id', $id_orden)->first();

  $configuracion = AlpConfiguracion::where('id', '1')->first();

  $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

  MercadoPago::setClientId($almacen->id_mercadopago);
  MercadoPago::setClientSecret($almacen->key_mercadopago);
              
  if ($almacen->mercadopago_sand=='1') {

      MercadoPago::setPublicKey($almacen->public_key_mercadopago_test);
    
    }

    if ($almacen->mercadopago_sand=='2') {

      MercadoPago::setPublicKey($almacen->public_key_mercadopago);
      
    }

     $preference = MercadoPago::get("/v1/payments/search?external_reference=".$orden->referencia_mp);

   //  dd($preference);

     if(isset($preference['body']['results'])){
     
        foreach ($preference['body']['results'] as $r) {

              if ($r['status']=='in_process' || $r['status']=='pending') {
                  
                $idpago=$r['id'];

                $preference_data_cancelar=array([
                  'status'=>'cancelled'
                ]);

                $preference_data_cancelar = '{"status": "cancelled"}';

                $at=Mercadopago::getAccessToken();

                //dd($at);


               // $pre = MercadoPago::put("/v1/payments/".$idpago."", $preference_data_cancelar);

               // dd($pre);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments/'.$idpago.'');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

                curl_setopt($ch, CURLOPT_POSTFIELDS, $preference_data_cancelar);

                $headers = array();
                $headers[] = 'Authorization: Bearer '.$at;
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);

                $res=json_decode($result);

                $data_cancelar = array(
                  'id_orden' => $orden->id, 
                  'id_forma_pago' => $orden->id_forma_pago, 
                  'id_estatus_pago' => 4, 
                  'monto_pago' => $orden->monto_total, 
                  'json' => json_encode($res), 
                  'id_user' => '1'
                );

                AlpPagos::create($data_cancelar);

                $data_history_json = array(
                  'id_orden' => $orden->id, 
                  'id_status' =>'4', 
                  'notas' => 'Cancelacion de pago en Mercadopago', 
                  'json' => json_encode($res), 
                  'id_user' => '1' 
              );

              $history=AlpOrdenesHistory::create($data_history_json);


            }

         }
      }

}







}
