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

        $this->CancelarOrdenCompramas();
        #$this->CancelarMercadopago();

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

  $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.cantidad as cantidad_producto')
    ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
    ->where('alp_ordenes_detalle.id_orden', $orden->id)
    ->get();

              $productos = array();

              $peso=0;

              foreach ($detalles as $d) {

                $iva=0;

                if($d->monto_impuesto>0){

                  $iva=19;

                }

                $peso=$peso+$d->cantidad_producto;

                if ($d->precio_unitario>0) {

                  $dt = array(
                    'sku' => $d->referencia_producto, 
                    'name' => $d->nombre_producto, 
                    'url_img' => $d->imagen_producto, 
                    'value' => $d->precio_unitario, 
                    'value_prom' => $d->precio_unitario, 
                    'iva' => intval($iva), 
                    'quantity' => $d->cantidad
                  );

                  $productos[]=$dt;
                 
                }else{

                    if (substr($d->referencia_producto, 0,1)=='R') {
                       $dt = array(
                      'sku' => $d->referencia_producto, 
                      'name' => $d->nombre_producto, 
                      'url_img' => $d->imagen_producto, 
                      'value' => $d->precio_unitario, 
                      'value_prom' => $d->precio_unitario, 
                      'iva' => intval($iva),   
                      'quantity' => $d->cantidad
                    );

                    $productos[]=$dt;

                  }


                  $pc=AlpProductos::where('id', $d->id_combo)->first();

                  if (isset($pc->id)) {

                      if ($pc->tipo_producto=='3') {

                          
                        
                           $dt = array(
                          'sku' => $d->referencia_producto, 
                          'name' => $d->nombre_producto, 
                          'url_img' => $d->imagen_producto, 
                          'value' => $d->precio_unitario, 
                          'value_prom' => $d->precio_unitario, 
                          'iva' => intval($iva),  
                          'quantity' => $d->cantidad
                        );

                        $productos[]=$dt;

                      # code...
                    }

                  }

                }
            }

          $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->where('users.id', '=', $orden->id_user)->first();


          $direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id', $orden->id_address)->withTrashed()->first();


          $dir = array(
            'ordenId' => $orden->referencia, 
            'ciudad' => $direccion->state_name, 
            'telefonoCliente' => $cliente->telefono_cliente, 
            'correoCliente' => $cliente->email, 
            'identificacionCliente' => $cliente->doc_cliente, 
            'nombreCliente' => $cliente->first_name." ".$cliente->last_name, 
            'direccionCliente' => $direccion->nombre_estructura." ".$direccion->principal_address." - ".$direccion->secundaria_address." ".$direccion->edificio_address." ".$direccion->detalle_address." ".$direccion->barrio_address, 
            'observacionDomicilio' => "", 
            'formaPago' => $formapago->nombre_forma_pago
          );

          $cupones=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

          $descuentoicg=AlpOrdenesDescuentoIcg::where('id_orden','=', $orden->id)->get();

          $descuento_total=0;

         # echo 'Envio a velocity 1    / ';

          foreach($descuentoicg as $di){

            $descuento_total=$descuento_total+$di->monto_descuento;
          }

          foreach($cupones as $co){

            $descuento_total=$descuento_total+$co->monto_descuento;
          }

          $o = array(
            'tipoServicio' => 1, 
            'retorno' => "false", 
            'formaPago' => $formapago->nombre_forma_pago,
            'totalFactura' => $orden->monto_total, 
            'subTotal' => number_format($orden->monto_total-$orden->monto_impuesto, 2, '.', ''),
            'iva' => $orden->monto_impuesto, 
            'descuento' => $descuento_total, 
            'peso' => $peso, 
            'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
            'horaMinPedido' => "00:00", 
            'horaMaxPedido' => "00:00", 
            'observaciones' => "", 
            'paradas' => $dir, 
            'products' => $productos, 
          );

       #  dd($o);


    $dataraw=json_encode($o);

  #  echo "data / ".$dataraw;

    $url= 'https://ff.logystix.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel';

   # echo $dataraw.' - ';

    $orden->update(['send_json_masc'=>$dataraw]);

    Log::info($dataraw);

    activity()->withProperties($dataraw)->log('Envio Aprobado Velocity '.$orden->id.' .vp634');

  $ch = curl_init();

  #curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago?warehouse_id='.$almacen->codigo_almacen);
  #curl_setopt($ch, CURLOPT_URL, 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');
  curl_setopt($ch, CURLOPT_URL, 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  #curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  
  curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
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





}
